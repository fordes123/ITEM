FROM php:8.1.13-fpm-alpine
RUN apk update \
    && apk add alpine-conf --no-cache \
    && /sbin/setup-timezone -z Asia/Shanghai \
    && apk del alpine-conf \
    && docker-php-ext-install pdo pdo_mysql calendar \
    && printf '[PHP]\ndate.timezone = Asia/Shanghai\n' >/usr/local/etc/php/conf.d/tzone.ini
