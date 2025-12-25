#!/bin/sh
set -e

cd /var/www/html

# Pastikan folder writable ada
mkdir -p storage bootstrap/cache

# Fix permission untuk bind-mount (sering root-owned setelah pull)
chown -R www-data:www-data storage bootstrap/cache || true
chmod -R ug+rwX storage bootstrap/cache || true

# Kalau kamu pakai database untuk cache/session/queue, tabel harus ada lewat migrate
# Jangan migrate otomatis di entrypoint kecuali kamu yakin.

exec "$@"
