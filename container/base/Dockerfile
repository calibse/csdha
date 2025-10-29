FROM alpine:3.22

RUN apk add --no-cache php83 php83-fpm composer
RUN apk add --no-cache php83-pdo_mysql php83-gd php83-zip php83-session php83-fileinfo php83-tokenizer php83-dom php83-pecl-imagick php83-pecl-memcached
RUN apk add --no-cache unzip git rclone
RUN apk add --no-cache weasyprint
RUN apk add --no-cache libqrencode-tools
RUN apk add --no-cache imagemagick
RUN apk add --no-cache font-cantarell
RUN apk add --no-cache rsvg-convert
COPY container/config/php-fpm/www-overrides.conf /etc/php83/php-fpm.d/z-www-overrides.conf
COPY rclone.conf /root/.config/rclone/
RUN addgroup csdha
RUN adduser -h /var/www/app -g csdha -G csdha -S -D -H csdha
WORKDIR /var/www/app

