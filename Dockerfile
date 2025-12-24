# ---------- Base PHP build (with required PHP extensions) ----------
FROM php:8.3-fpm-alpine AS php_base
WORKDIR /var/www/html

# Minimal libs for our required PHP extensions:
# - mbstring needs oniguruma
# - sodium needs libsodium
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

# ---------- Vendor stage (composer install on correct platform) ----------
FROM php_base AS vendor
WORKDIR /var/www/html

# Install composer (minimal, official installer)
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --no-scripts

# Now copy full source for autoload optimization if needed
COPY . .
RUN composer dump-autoload --optimize --no-dev


# ---------- Assets stage (Vite build) ----------
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build


# ---------- Runtime stage ----------
FROM php_base AS app
WORKDIR /var/www/html

COPY . .
COPY --from=vendor /var/www/html/vendor ./vendor
COPY --from=assets /app/public/build ./public/build

COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# (Optional) entrypoint minimal (cache only). Kalau kamu mau tanpa entrypoint, bilang.
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

USER www-data
ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm", "-F"]
