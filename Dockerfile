FROM alpine:3.22

RUN apk add --no-cache php83 php83-fpm composer apache2 apache2-proxy
RUN apk add --no-cache unzip nano
RUN apk add --no-cache php83-pdo_mysql php83-gd php83-zip php83-session php83-fileinfo php83-tokenizer php83-dom 

WORKDIR /var/www/app
COPY composer.json composer.lock . 
RUN composer install --no-dev --no-scripts --no-autoloader --no-progress -n && composer clear-cache

COPY container/apache/main-site.conf /etc/apache2/conf.d/main-site.conf
# RUN a2enmod remoteip

RUN apk add --no-cache weasyprint
RUN apk add --no-cache libqrencode-tools
RUN apk add --no-cache imagemagick 
RUN apk add --no-cache font-cantarell 

COPY container/php-fpm/www-overrides.conf /etc/php83/php-fpm.d/z-www-overrides.conf

WORKDIR /var/www/app
COPY . .
RUN composer install --no-dev --no-progress -n && composer clear-cache

CMD ["/var/www/app/container/deploy"]

