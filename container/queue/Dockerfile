FROM alpine-laravel:1.0

COPY container/bin/check_dir /app/
COPY container/queue/entrypoint /app/
ENTRYPOINT ["/app/check_dir"]
USER csdha
CMD ["php", "artisan", "queue:work"]

