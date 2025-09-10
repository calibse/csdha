FROM alpine:3.22

RUN apk add --no-cache php83 php83-fpm composer apache2 apache2-proxy
RUN apk add --no-cache unzip nano
RUN apk add --no-cache php83-pdo_mysql php83-gd php83-zip php83-session php83-fileinfo php83-tokenizer php83-dom 

WORKDIR /var/www/app
COPY composer.json composer.lock . 
RUN composer install --no-dev --no-scripts --no-autoloader --no-progress -n && composer clear-cache

