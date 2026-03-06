# syntax=docker/dockerfile:1

# --- Stage 1: Frontend Builder ---
FROM node:18-alpine AS frontend-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci --prefer-offline --no-audit
COPY vite.config.js postcss.config.js tailwind.config.js ./
COPY resources ./resources
RUN npm run build

# --- Stage 2: Composer Builder ---
FROM composer:2 AS composer-builder
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-scripts \
    --no-autoloader \
    --ignore-platform-reqs
COPY . .
RUN composer dump-autoload --optimize --classmap-authoritative

# --- Stage 3: PHP Base ---
FROM php:8.4-fpm-alpine AS php-fpm

# Install System dependencies
RUN apk add --no-cache \
    freetype-dev libjpeg-turbo-dev libpng-dev libzip-dev \
    oniguruma-dev nginx supervisor sed bash \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql mbstring exif pcntl bcmath gd zip opcache

# ✅ FIX: FORCE CREATE PHP-FPM CONFIGS (Manually writing files)
RUN mkdir -p /usr/local/etc/php-fpm.d && \
    printf "[global]\n\
error_log = /var/log/php-fpm.log\n\
include=/usr/local/etc/php-fpm.d/*.conf\n" > /usr/local/etc/php-fpm.conf && \
    printf "[www]\n\
user = www-data\n\
group = www-data\n\
listen = 127.0.0.1:9000\n\
pm = dynamic\n\
pm.max_children = 5\n\
pm.start_servers = 2\n\
pm.min_spare_servers = 1\n\
pm.max_spare_servers = 3\n" > /usr/local/etc/php-fpm.d/www.conf

# Install Redis & MongoDB
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis mongodb \
    && docker-php-ext-enable redis mongodb \
    && apk del .build-deps

WORKDIR /var/www/html

# Copy Project Files
COPY --chown=www-data:www-data . .
COPY --from=composer-builder --chown=www-data:www-data /app/vendor ./vendor
COPY --from=frontend-builder --chown=www-data:www-data /app/public/build ./public/build

# Setup Directories and Permissions
RUN mkdir -p storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache \
    storage/logs \
    bootstrap/cache \
    database/json \
    /run/nginx \
    /var/log/supervisor \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache database public

# --- Stage 4: Production ---
FROM php-fpm AS production
USER root

# Nginx Config
RUN cat > /etc/nginx/http.d/default.conf <<'EOF'
server {
    listen 80;
    server_name _;
    root /var/www/html/public;
    index index.php index.html;
    disable_symlinks off;
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_read_timeout 300;
    }
}
EOF

# Supervisor Config
RUN cat > /etc/supervisord.conf <<'EOF'
[supervisord]
nodaemon=true
user=root
logfile=/var/log/supervisor/supervisord.log
pidfile=/var/run/supervisord.pid

[program:php-fpm]
command=/usr/local/sbin/php-fpm -F -R -y /usr/local/etc/php-fpm.conf
autostart=true
autorestart=true
priority=5
stdout_logfile=/dev/stdout
stderr_logfile=/dev/stderr

[program:nginx]
command=/usr/sbin/nginx -g "daemon off;"
autostart=true
autorestart=true
priority=10
stdout_logfile=/dev/stdout
stderr_logfile=/dev/stderr

[program:laravel-queue]
command=php /var/www/html/artisan queue:work --sleep=3 --tries=3
directory=/var/www/html
user=www-data
autostart=true
autorestart=true
stdout_logfile=/dev/stdout
stderr_logfile=/dev/stderr
EOF

# Entrypoint setup
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh \
    && sed -i 's/\r$//' /usr/local/bin/docker-entrypoint.sh

EXPOSE 80
ENTRYPOINT ["/bin/sh", "/usr/local/bin/docker-entrypoint.sh"]
