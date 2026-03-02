#!/bin/sh
set -e

# 1. Windows Line Ending Fix
sed -i 's/\r$//' /usr/local/bin/docker-entrypoint.sh 2>/dev/null || true

# 2. Permissions Fix
echo "🔧 Setting permissions for Laravel folders..."
chown -R www-data:www-data /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    /var/www/html/database \
    /var/www/html/public

chmod -R 775 /var/www/html/storage \
    /var/www/html/bootstrap/cache \
    /var/www/html/database \
    /var/www/html/public

# 3. Wait for MongoDB
echo "⏳ Waiting for MongoDB connection..."
MAX_TRIES=20
COUNT=0
until php artisan tinker --execute="try { DB::connection()->getDatabaseName(); echo 'ok'; } catch (\Exception \$e) { exit(1); }" 2>/dev/null | grep -q "ok"; do
    COUNT=$((COUNT+1))
    if [ $COUNT -gt $MAX_TRIES ]; then
        echo "⚠️ MongoDB timeout: App starting anyway..."
        break
    fi
    echo "🔄 MongoDB not ready ($COUNT/$MAX_TRIES), retrying..."
    sleep 2
done
echo "✅ MongoDB ready!"

# 4. Laravel Optimizations
echo "🚀 Optimizing Laravel..."
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true

php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true

# 5. Storage Symlink
echo "🔗 Setting up storage symlink..."
rm -rf public/storage 2>/dev/null || true
php artisan storage:link --force 2>/dev/null || true

# 6. Fix Supervisor Config - Ensure php-fpm command is correct
echo "⚙️ Configuring supervisor..."
cat > /etc/supervisord.conf <<'SUPERVISOR_EOF'
[supervisord]
nodaemon=true
user=root
logfile=/var/log/supervisor/supervisord.log
pidfile=/var/run/supervisord.pid

[program:php-fpm]
command=/usr/local/sbin/php-fpm --allow-to-run-as-root --nodaemonize
autostart=true
autorestart=true
priority=5
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0

[program:nginx]
command=/usr/sbin/nginx -g "daemon off;"
autostart=true
autorestart=true
priority=10
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0

[program:laravel-queue]
command=php /var/www/html/artisan queue:work --sleep=3 --tries=3
directory=/var/www/html
user=www-data
autostart=true
autorestart=true
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0

[program:laravel-schedule]
command=sh -c "while true; do php /var/www/html/artisan schedule:run --verbose --no-interaction; sleep 60; done"
directory=/var/www/html
user=www-data
autostart=true
autorestart=true
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0
SUPERVISOR_EOF

# 7. Start Supervisord
echo "🏁 Starting supervisord..."
exec /usr/bin/supervisord -n -c /etc/supervisord.conf