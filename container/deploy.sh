#! /bin/sh

cd /var/www/app
php artisan migrate --force
php artisan db:audit-triggers
apache2-foreground

