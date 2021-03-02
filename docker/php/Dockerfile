FROM php:7.4-fpm

RUN docker-php-ext-install pdo pdo_mysql

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.log=/usr/share/nginx/app/xdebuglog/log.txt" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN apt-get update && \
    apt-get install -y \
        libzip-dev \
        libpng-dev \
        zlib1g-dev

RUN docker-php-ext-install zip
RUN docker-php-ext-install gd
