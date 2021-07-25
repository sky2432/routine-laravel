FROM php:7.4.1-fpm

# php.iniをコピー
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

# Composer install
COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer

RUN apt-get update \
    && apt-get install -y \
    git \
    zip \
    unzip \
    vim \
    && docker-php-ext-install pdo_mysql bcmath

WORKDIR /var/www/html