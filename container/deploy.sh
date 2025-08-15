#! /bin/sh

cd /var/www/app
git pull
composer install --no-dev && composer clear-cache
php artisan migrate --force
php artisan db:audit-triggers
php artisan optimize:clear
php artisan optimize
chown -R www-data bootstrap/cache storage
apache2-foreground

