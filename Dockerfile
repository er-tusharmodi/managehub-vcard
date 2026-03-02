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
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    nginx \
    supervisor \
    sed \
    bash \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql mbstring exif pcntl bcmath gd zip opcache

# Install Redis & MongoDB
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis mongodb \
    && docker-php-ext-enable redis mongodb \
    && apk del .build-deps

# PHP Optimized Config
RUN cat > /usr/local/etc/php/conf.d/zz-laravel.ini <<'EOF'
memory_limit=256M
upload_max_filesize=20M
post_max_size=20M
max_execution_time=300
display_errors=Off
log_errors=On
expose_php=Off
date.timezone=UTC
EOF

WORKDIR /var/www/html

# Copy Project Files
COPY --chown=www-data:www-data . .
COPY --from=composer-builder --chown=www-data:www-data /app/vendor ./vendor
COPY --from=frontend-builder --chown=www-data:www-data /app/public/build ./public/build

# ✅ Setup Directories and Permissions (Added public and database fix)
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

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_read_timeout 300;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

# ✅ Supervisor Config (Using absolute paths to avoid "Usage" error)
RUN cat > /etc/supervisord.conf <<'EOF'
[supervisord]
nodaemon=true
user=root
logfile=/var/log/supervisor/supervisord.log
pidfile=/var/run/supervisord.pid

[program:php-fpm]
command=/usr/local/sbin/php-fpm -F
autostart=true
autorestart=true
priority=5
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0

[program:nginx]
command=/usr/sbin/nginx -g "daemon off;"
autostart=true
autorestart=true
priority=10
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0

[program:laravel-queue]
command=/usr/local/bin/php /var/www/html/artisan queue:work --sleep=3 --tries=3 --max-time=3600
directory=/var/www/html
user=www-data
autostart=true
autorestart=true
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0

[program:laravel-schedule]
command=/bin/sh -c "while true; do /usr/local/bin/php /var/www/html/artisan schedule:run --verbose --no-interaction; sleep 60; done"
directory=/var/www/html
user=www-data
autostart=true
autorestart=true
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0
EOF

# Entrypoint setup with Windows Line Ending Fix
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh \
    && sed -i 's/\r$//' /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

# ✅ Using Absolute Path for Entrypoint
ENTRYPOINT ["/bin/sh", "/usr/local/bin/docker-entrypoint.sh"]