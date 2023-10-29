FROM php:8.0-alpine

COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

ARG WWW_DATA_UID
ARG WWW_DATA_GID

RUN apk --no-cache add shadow

RUN usermod -u ${WWW_DATA_UID} www-data \
    && groupmod -g ${WWW_DATA_GID} www-data

RUN apk add --no-cache ${PHPIZE_DEPS}

RUN apk add --update linux-headers \
    && curl --insecure -L https://pecl.php.net/get/xdebug-3.2.2.tgz -o /tmp/xdebug.tgz \
    && pecl install --offline /tmp/xdebug.tgz \
    && docker-php-ext-enable xdebug

RUN docker-php-ext-install pcntl

RUN apk add --no-cache git
