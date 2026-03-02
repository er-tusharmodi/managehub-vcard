#!/bin/sh
set -e

# 1. Windows Line Ending Fix (Script ke andar hi protection)
# Isse agar koi hidden \r character hoga toh wo hat jayega
sed -i 's/\r$//' /usr/local/bin/docker-entrypoint.sh

# 2. Permissions Fix
echo "🔧 Setting permissions for Laravel folders..."
# Poore folder ka owner ek baar mein set karna safer hai
chown -R www-data:www-data /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    /var/www/html/database \
    /var/www/html/public

chmod -R 775 /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    /var/www/html/database \
    /var/www/html/public

# 3. Wait for MongoDB (Using a more stable check)
echo "⏳ Waiting for MongoDB connection..."
MAX_TRIES=20
COUNT=0
# Connection check: Hum seedha database list karne ki koshish karenge
until php artisan tinker --execute="try { DB::connection()->getDatabaseName(); echo 'connected'; } catch (\Exception \$e) { exit(1); }" | grep -q "connected"; do
    COUNT=$((COUNT+1))
    if [ $COUNT -gt $MAX_TRIES ]; then
        echo "⚠️ MongoDB timeout: App starting without DB verification."
        break
    fi
    echo "🔄 MongoDB not ready ($COUNT/$MAX_TRIES), retrying in 3s..."
    sleep 3
done
echo "✅ MongoDB is reachable!"

# 4. Laravel Optimizations (The Clean Way)
echo "🚀 Optimizing Laravel for Production..."
# Sabse pehle purana kachra saaf karein
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Naya cache banayein
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Storage Symlink Fix (CSS/Images load hone ke liye)
echo "🔗 Refreshing storage symlink..."
rm -rf public/storage
php artisan storage:link --force || true

# 6. Final Command: Start Supervisord
# Hum absolute path use karenge taaki environment variable ka koi issue na ho
echo "🏁 All systems go! Starting Supervisord..."
exec /usr/bin/supervisord -n -c /etc/supervisord.conf