#!/bin/sh
set -e

# 1. Permissions Fix (Image upload aur 500 error se bachne ke liye)
echo "🔧 Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# 2. Wait for MongoDB (Timeout protection ke saath)
echo "⏳ Waiting for MongoDB..."
MAX_TRIES=15
COUNT=0
# Tinker command ko simplified rakha hai taaki binary conflict na ho
until php artisan tinker --execute="DB::connection()->getPdo();" > /dev/null 2>&1; do
    COUNT=$((COUNT+1))
    if [ $COUNT -gt $MAX_TRIES ]; then
        echo "⚠️ MongoDB taking too long. Starting services anyway..."
        break
    fi
    echo "🔄 MongoDB not ready ($COUNT/$MAX_TRIES), waiting 3s..."
    sleep 3
done
echo "✅ MongoDB check complete!"

# 3. Laravel Optimizations
echo "🚀 Running Laravel optimizations..."
# Clear caches first to avoid old config issues
php artisan config:clear
php artisan route:clear

# Re-cache for production speed
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ensure storage link exists (Important for CSS/Images)
php artisan storage:link --force || true

# 4. Start Supervisord (Ye line error fix karegi)
echo "🏁 Starting all services via Supervisord..."
exec /usr/bin/supervisord -n -c /etc/supervisord.conf