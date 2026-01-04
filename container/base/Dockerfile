FROM alpine:3.22

RUN apk add --no-cache php84 php84-fpm composer
RUN apk add --no-cache php84-pdo_mysql php84-gd php84-zip php84-session php84-fileinfo php84-tokenizer php84-dom php84-exif php84-pecl-memcached
RUN apk add --no-cache unzip git rclone
COPY container/config/php-fpm/www-overrides.conf /etc/php84/php-fpm.d/z-www-overrides.conf
COPY rclone.conf /root/.config/rclone/
RUN addgroup csdha
RUN adduser -h /var/www/app -g csdha -G csdha -S -D -H csdha
WORKDIR /var/www/app

