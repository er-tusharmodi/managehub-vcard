#!/bin/sh
set -e

# 1. Permissions Fix (Sabse zaroori step)
echo "🔧 Setting permissions..."
# database folder ke sath vcard-template (agar hai) uski bhi permission fix
chown -R www-data:www-data /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    /var/www/html/database \
    /var/www/html/public

chmod -R 775 /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    /var/www/html/database \
    /var/www/html/public

# 2. Wait for MongoDB (Better handling)
echo "⏳ Waiting for MongoDB..."
MAX_TRIES=20
COUNT=0
# Connection check bina tinker ke zyada stable rehta hai starting mein
until php artisan tinker --execute="DB::connection()->getDatabaseName();" > /dev/null 2>&1; do
    COUNT=$((COUNT+1))
    if [ $COUNT -gt $MAX_TRIES ]; then
        echo "⚠️ MongoDB timeout. Check your .env settings later."
        break
    fi
    echo "🔄 MongoDB not ready ($COUNT/$MAX_TRIES), waiting 3s..."
    sleep 3
done
echo "✅ MongoDB check complete!"

# 3. Laravel Optimizations
echo "🚀 Running Laravel optimizations..."
# Pehle clear karein taaki purani config conflict na kare
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Production ke liye cache banayein
php artisan config:cache
php artisan route:cache
php artisan view:cache

# CSS aur Images ke liye link create karein
echo "🔗 Creating storage symlink..."
php artisan storage:link --force || true

# 4. Start Supervisord
# -n flag ensure karta hai ki container foreground mein chalta rahe
echo "🏁 Starting all services via Supervisord..."
exec /usr/bin/supervisord -n -c /etc/supervisord.conf