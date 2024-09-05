FROM caddy/caddy:alpine

VOLUME [ "/var/www/typecho" ]
COPY config.inc.php /var/www/html/typecho/

RUN apk update \
    && apk add alpine-conf wget unzip --no-cache \
    && /sbin/setup-timezone -z Asia/Shanghai \
    && wget https://github.com/typecho/typecho/releases/latest/download/typecho.zip \
    && mkdir -p /var/www/html/typecho \
    && unzip typecho.zip -d /var/www/html/typecho \
    && rm typecho.zip \
    && apk del alpine-conf wget unzip