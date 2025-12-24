#!/bin/sh
set -e

cd /var/www/html

# Safety: APP_KEY wajib ada untuk production
if [ -z "${APP_KEY:-}" ]; then
  echo "ERROR: APP_KEY is empty. Set APP_KEY in your .env before running."
  exit 1
fi

# Cache config/route/view untuk performance (production)
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# OPTIONAL: jalankan migrate saat start kalau kamu mau "tinggal start"
# Aktifkan dengan RUN_MIGRATIONS=true di docker-compose.yml
if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
  echo "Running migrations..."
  php artisan migrate --force
fi

exec "$@"
