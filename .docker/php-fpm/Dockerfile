FROM php:8.1.13-fpm-alpine
RUN apk update \
    && apk add tzdata --no-cache \
    && ln -sf "/usr/share/zoneinfo/$TZ" /etc/localtime \
    && echo "$TZ" > /etc/timezone \
    && docker-php-ext-install pdo pdo_mysql calendar \
    && printf '[PHP]\ndate.timezone = Asia/Shanghai\n' >/usr/local/etc/php/conf.d/tzone.ini
