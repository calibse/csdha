FROM alpine-laravel:1.0

RUN apk add --no-cache rclone
COPY rclone.conf /root/.config/rclone/
COPY .env.production .
COPY .env.production-admin .
CMD ["container/bin/sync"]

