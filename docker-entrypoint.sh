#!/bin/sh
set -e

# 1. Permissions Fix (Sabse zaroori step image upload ke liye)
# Container start hote hi folders ko www-data ka malik bana do
echo "Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 2. Wait for MongoDB (Improved)
echo "Waiting for MongoDB..."
# Hum 30 seconds tak try karenge, warna skip karke aage badhenge
MAX_TRIES=15
COUNT=0
until php artisan tinker --execute="try { DB::connection()->command(['ping' => 1]); echo 'OK'; } catch (\Exception \$e) { exit(1); }" | grep -q "OK"; do
    COUNT=$((COUNT+1))
    if [ $COUNT -gt $MAX_TRIES ]; then
        echo "⚠️ MongoDB taking too long. Starting app anyway..."
        break
    fi
    echo "MongoDB not ready ($COUNT/$MAX_TRIES), waiting..."
    sleep 3
done
echo "MongoDB check complete!"

# 3. Laravel optimizations
# Tip: Production mein 'config:cache' se pehle 'config:clear' karna safer hota hai
echo "Running Laravel optimizations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
# Storage link agar nahi hai toh bana do
php artisan storage:link --force || true

# Start supervisord
echo "Starting services..."
exec /usr/bin/supervisord -c /etc/supervisord.conf