# ---------- Composer deps ----------
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress
COPY . .
RUN composer dump-autoload --optimize --no-dev

# ---------- Vite build ----------
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# ---------- Runtime: PHP-FPM (production) ----------
FROM php:8.3-fpm-alpine AS app
WORKDIR /var/www/html

# Minimal libs for required PHP extensions on Alpine:

RUN apk add --no-cache \
    oniguruma \
    libsodium \
    && apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    oniguruma-dev \
    libsodium-dev \
    && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    sockets \
    sodium \
    opcache \
    && apk del .build-deps

# Copy app
COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build

# PHP opcache config
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Entrypoint
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Laravel writable dirs
RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

USER www-data
ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm", "-F"]
