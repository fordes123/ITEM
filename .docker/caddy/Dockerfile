FROM caddy/caddy:alpine

VOLUME [ "/var/www/typecho" ]
COPY config.inc.php /var/www/html/typecho/

ENV TZ Asia/Shanghai
RUN apk update \
    && apk add tzdata wget unzip --no-cache \
    && ln -sf "/usr/share/zoneinfo/$TZ" /etc/localtime \
    && echo "$TZ" > /etc/timezone \
    && wget https://github.com/typecho/typecho/releases/latest/download/typecho.zip \
    && mkdir -p /var/www/html/typecho \
    && unzip typecho.zip -d /var/www/html/typecho \
    && rm typecho.zip \
    && apk del wget unzip
