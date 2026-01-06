FROM alpine-laravel:1.0

COPY container/bin/queue_check_dir /app/
COPY container/queue/entrypoint /app/
ENTRYPOINT ["/app/queue_check_dir"]
COPY container/config/crontabs/csdha /etc/crontabs/csdha
RUN chown csdha:csdha /etc/crontabs/csdha
RUN chmod 600 /etc/crontabs/csdha
USER csdha
CMD ["crond"]

