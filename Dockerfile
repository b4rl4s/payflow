FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
        icu-dev \
        libzip-dev \
        $PHPIZE_DEPS \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) intl mysqli pdo pdo_mysql opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del $PHPIZE_DEPS

COPY docker/php-fpm-payflow.ini /usr/local/etc/php/conf.d/payflow.ini

WORKDIR /var/www/app
