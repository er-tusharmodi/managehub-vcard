#!/bin/sh
set -e

# Wait for MongoDB
echo "Waiting for MongoDB..."
until php artisan tinker --execute="DB::connection()->command(['ping' => 1]);" > /dev/null 2>&1; do
    echo "MongoDB not ready, waiting..."
    sleep 2
done
echo "MongoDB is ready!"

# Check if APP_KEY is set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:YHyJ6483MHkEaYCxXkZ1iaQw2oJGlm3uZ2fKzYc6lCI=" ]; then
    echo "⚠️  Warning: Using default APP_KEY. Set a proper key in production!"
fi

# Run Laravel optimizations
echo "Running Laravel optimizations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start supervisord
echo "Starting services..."
exec /usr/bin/supervisord -c /etc/supervisord.conf
