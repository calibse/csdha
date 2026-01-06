#! /bin/sh

echo 'cron started'
while true 
do
	flock -n /tmp/laravel-queue.lock php -d max_execution_time=120 artisan queue:work --once -q
	sleep 60
done