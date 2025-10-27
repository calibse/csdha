FROM alpine-laravel:1.0

RUN apk add --no-cache weasyprint
RUN apk add --no-cache libqrencode-tools
RUN apk add --no-cache imagemagick 
RUN apk add --no-cache font-cantarell 
RUN apk add --no-cache rsvg-convert
COPY container/config/php-fpm/www-overrides.conf /etc/php83/php-fpm.d/z-www-overrides.conf
ENTRYPOINT ["container/web/entrypoint"]
CMD ["container/bin/deploy"]

