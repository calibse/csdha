FROM alpine-laravel:1.0

WORKDIR /var/www/app
COPY . .
RUN composer install --optimize-autoloader --prefer-dist --no-dev --no-progress -n && composer clear-cache

ENTRYPOINT ["container/migrate/entrypoint"]
CMD ["container/bin/migrate"]

