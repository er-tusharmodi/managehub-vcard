# syntax=docker/dockerfile:1

#############################################
# Build frontend assets
#############################################
FROM node:18-alpine AS frontend-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci --prefer-offline --no-audit
COPY vite.config.js postcss.config.js tailwind.config.js ./
COPY resources ./resources
RUN npm run build

#############################################
# Install PHP dependencies
#############################################
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

#############################################
# PHP-FPM Runtime
#############################################
FROM php:8.2-fpm-alpine AS php-fpm

# Install dependencies and PHP extensions
RUN apk add --no-cache \
    bash \
    curl \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        opcache

# Install Redis extension
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# PHP configuration
COPY --from=composer-builder /usr/bin/composer /usr/bin/composer
COPY docker/php/php.ini /usr/local/etc/php/conf.d/laravel.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www/html

# Copy application
COPY --chown=www-data:www-data . .
COPY --from=composer-builder --chown=www-data:www-data /app/vendor ./vendor
COPY --from=frontend-builder --chown=www-data:www-data /app/public/build ./public/build

# Create required directories
RUN mkdir -p storage/framework/sessions \
    storage/framework/views \
    storage/framework/cache \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

USER www-data

EXPOSE 9000

#############################################
# Nginx
#############################################
FROM nginx:1.25-alpine AS nginx

COPY docker/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY --from=php-fpm /var/www/html/public /var/www/html/public

EXPOSE 80

#############################################
# Final production image with supervisor
#############################################
FROM php-fpm AS production

USER root

# Copy Nginx
COPY --from=nginx /usr/sbin/nginx /usr/sbin/nginx
COPY --from=nginx /etc/nginx /etc/nginx
COPY --from=nginx /var/log/nginx /var/log/nginx
COPY --from=nginx /var/cache/nginx /var/cache/nginx
COPY --from=nginx /usr/lib/nginx /usr/lib/nginx

# Supervisor config
COPY docker/supervisor/supervisord.conf /etc/supervisord.conf

# Laravel optimization
RUN php artisan config:cache || true \
    && php artisan route:cache || true \
    && php artisan view:cache || true

EXPOSE 80 9000

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
