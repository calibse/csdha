FROM alpine-laravel:1.0

COPY .env.production /root/
COPY .env.production-admin /root/
COPY container/bin/init /root/
CMD ["/root/init"]

