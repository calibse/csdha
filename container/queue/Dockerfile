FROM alpine-laravel:1.0

COPY container/bin/checkdir /root/
COPY container/queue/entrypoint /root/
ENTRYPOINT ["/root/checkdir"]
CMD ["container/bin/start_queue"]

