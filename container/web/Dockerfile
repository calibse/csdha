FROM alpine-laravel:1.0

COPY container/bin/check_dir /root/
COPY container/web/entrypoint /root/
ENTRYPOINT ["/root/check_dir"]
CMD ["php-fpm84", "-F"]

