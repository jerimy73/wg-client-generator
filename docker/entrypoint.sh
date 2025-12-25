#!/bin/sh
set -e
cd /var/www/html

if [ -z "${APP_KEY:-}" ]; then
  echo "WARNING: APP_KEY is empty. Container will start, generate APP_KEY manually with artisan."
fi


php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

exec "$@"