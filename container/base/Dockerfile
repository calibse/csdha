FROM alpine:3.22

RUN apk add --no-cache php83 php83-fpm composer
RUN apk add --no-cache php83-pdo_mysql php83-gd php83-zip php83-session php83-fileinfo php83-tokenizer php83-dom php83-pecl-imagick php83-pecl-memcached
RUN apk add --no-cache unzip git
WORKDIR /var/www/app

