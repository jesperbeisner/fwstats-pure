FROM php:8.1-fpm

RUN apt update

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions zip gd xdebug @composer

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

COPY "./.docker/php/dev/php-extra.ini" "$PHP_INI_DIR/conf.d/php-extra.ini"
COPY "./.docker/php/dev/php-xdebug.ini" "$PHP_INI_DIR/conf.d/php-extra.ini"

RUN sed -i "s/expose_php = On/expose_php = Off/g" "$PHP_INI_DIR/php.ini"
