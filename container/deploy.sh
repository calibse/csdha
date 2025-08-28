#! /bin/sh

cd /var/www/app
php artisan migrate --force
php artisan db:audit-triggers
php artisan optimize:clear
php artisan optimize
chown -R www-data bootstrap/cache storage
apache2-foreground

