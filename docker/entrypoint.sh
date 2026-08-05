#!/bin/sh
set -e

if [ ! -L /app/public/storage ]; then
    php artisan storage:link
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
