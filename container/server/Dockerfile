FROM alpine:3.23

RUN apk add --no-cache apache2 apache2-proxy
COPY container/config/apache/main-site.conf /etc/apache2/conf.d/main-site.conf
WORKDIR /var/www/app
COPY container/bin/check_dir /root/
COPY container/server/entrypoint /root/
ENTRYPOINT ["/root/check_dir"]
CMD ["httpd", "-D", "FOREGROUND"]

