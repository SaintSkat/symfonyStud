FROM php:8.2-fpm-alpine

RUN apk add --no-cache curl git build-base zlib-dev oniguruma-dev autoconf bash sudo postgresql-dev && docker-php-ext-install pdo pdo_pgsql

COPY ./ /var/www
WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer