FROM php:8.1-cli-alpine

COPY --from=composer:2.2.6 /usr/bin/composer /usr/local/bin

RUN apk add --update --no-cache --virtual .build-deps $PHPIZE_DEPS && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    apk del .build-deps

WORKDIR /app
