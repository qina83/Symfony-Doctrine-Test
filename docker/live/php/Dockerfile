
FROM php:7.4-fpm

RUN docker-php-ext-install pdo pdo_mysql

RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        libpng-dev \
        zlib1g-dev

RUN docker-php-ext-install zip
RUN docker-php-ext-install gd

RUN mkdir -p /usr/share/nginx/app
COPY ./ /usr/share/nginx/app
EXPOSE 9000
WORKDIR /usr/share/nginx/app