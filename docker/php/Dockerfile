FROM php:8.2-fpm-alpine

RUN apk update
RUN apk add  --no-cache git mysql-client curl libzip-dev libmcrypt libmcrypt-dev openssh-client icu-dev \
    libxml2-dev freetype-dev libpng-dev libjpeg-turbo-dev g++ make autoconf
RUN docker-php-source extract
RUN docker-php-source delete
RUN docker-php-ext-install pdo_mysql soap intl zip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN rm -rf /tmp/*

CMD ["php-fpm", "-F"]

WORKDIR /var/www/app

EXPOSE 9000
