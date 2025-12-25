# ---------- Base PHP build (with required PHP extensions) ----------
FROM php:8.3-fpm-alpine AS php_base
WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    oniguruma \
    libsodium \
    libpng \
    libzip \
    freetype \
    libjpeg-turbo \
    && apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    linux-headers \
    oniguruma-dev \
    libsodium-dev \
    libpng-dev \
    libzip-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    sockets \
    sodium \
    opcache \
    gd \
    zip \
    && docker-php-ext-enable opcache \
    && apk del .build-deps \
    && rm -rf /tmp/* /var/cache/apk/*

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ---------- Vendor stage (composer install on correct platform) ----------
FROM php_base AS vendor
WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-autoloader --no-scripts --prefer-dist

COPY . .
RUN composer dump-autoload --optimize --no-dev

# ---------- Assets stage (Vite build) ----------
FROM node:20-alpine AS assets
WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci --only=production
COPY . .
RUN npm run build

# ---------- Runtime stage ----------
FROM php_base AS app
WORKDIR /var/www/html

# Copy application files
COPY --from=vendor /var/www/html /var/www/html
COPY --from=assets /app/public/build ./public/build

# Copy configuration
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Create entrypoint
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Set permissions
RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

USER www-data

ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm", "-F"]