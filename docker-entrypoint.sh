# #!/bin/sh
# set -e

# # Wait for MongoDB
# echo "Waiting for MongoDB..."
# until php artisan tinker --execute="DB::connection()->command(['ping' => 1]);" > /dev/null 2>&1; do
#     echo "MongoDB not ready, waiting..."
#     sleep 2
# done
# echo "MongoDB is ready!"

# # Check if APP_KEY is set
# if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:YHyJ6483MHkEaYCxXkZ1iaQw2oJGlm3uZ2fKzYc6lCI=" ]; then
#     echo "⚠️  Warning: Using default APP_KEY. Set a proper key in production!"
# fi

# # Run Laravel optimizations
# echo "Running Laravel optimizations..."
# php artisan config:cache
# php artisan route:cache
# php artisan view:cache

# # Start supervisord
# echo "Starting services..."
# exec /usr/bin/supervisord -c /etc/supervisord.conf

###################################################################

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