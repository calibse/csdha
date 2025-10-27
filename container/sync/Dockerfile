FROM alpine-laravel:1.0

COPY .env.production .
COPY .env.production-admin .
ENTRYPOINT ["container/bin/sync"]

