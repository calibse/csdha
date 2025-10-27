FROM alpine:3.22

RUN apk add --no-cache apache2 apache2-proxy
COPY container/config/apache/main-site.conf /etc/apache2/conf.d/main-site.conf
WORKDIR /var/www/app
ENTRYPOINT ["container/server/entrypoint"]
CMD ["httpd", "-D", "FOREGROUND"]

