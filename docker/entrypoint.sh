#!/bin/sh
set -e
cd /var/www/html

if [ -z "${APP_KEY:-}" ]; then
  echo "ERROR: APP_KEY is empty. Set APP_KEY in .env"
  exit 1
fi

php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

exec "$@"