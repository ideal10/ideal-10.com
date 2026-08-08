#!/bin/sh
set -e

if [ ! -L /app/public/storage ]; then
    php artisan storage:link
fi

if [ "$DB_CONNECTION" = "sqlite" ]; then
    DB_DATABASE_PATH="${DB_DATABASE:-database/database.sqlite}"
    if [ ! -f "$DB_DATABASE_PATH" ]; then
        touch "$DB_DATABASE_PATH"
    fi
fi

php artisan migrate --force

php artisan config:cache
php artisan route:cache
php artisan view:cache

exec "$@"
