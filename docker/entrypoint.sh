#!/bin/sh

set -e

echo "Checking for RUN_MIGRATIONS flag..."

if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force
fi

if [ "$APP_KEY" = "" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

if [ ! -f storage/installed ]; then
    echo "Running initial setup..."
    
    # Cache configuration
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    
    # Create installed flag
    touch storage/installed
fi

echo "Starting PHP-FPM..."
exec "$@"