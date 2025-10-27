FROM alpine-laravel:1.0

COPY .env.production .
COPY .env.production-admin .
ENTRYPOINT ["container/install/entrypoint"]
CMD ["container/bin/install"]

