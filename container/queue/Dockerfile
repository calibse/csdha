FROM alpine-laravel:1.0

COPY container/bin/check_dir /root/
COPY container/queue/entrypoint /root/
ENTRYPOINT ["/root/check_dir"]
USER csdha
CMD ["php", "artisan", "queue:work"]

