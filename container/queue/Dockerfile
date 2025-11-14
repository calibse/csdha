FROM alpine-laravel:1.0

COPY container/bin/queue_check_dir /app/
COPY container/queue/entrypoint /app/
ENTRYPOINT ["/app/queue_check_dir"]
USER csdha
CMD ["php", "artisan", "queue:work"]

