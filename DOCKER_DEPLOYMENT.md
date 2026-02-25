# Docker Deployment Guide

## Quick Start

### Using Docker Compose (Recommended for Local Development)

```bash
# Copy environment file
cp .env.example .env

# Update .env with your settings (APP_KEY, DB credentials, etc.)

# Build and start containers
docker-compose up -d

# Run migrations
docker-compose exec app php artisan migrate --force

# Create storage link
docker-compose exec app php artisan storage:link

# Visit http://localhost:8080
```

### Using Docker Only (Production)

```bash
# Pull the latest image
docker pull ertusharmodi/managehub-vcard:latest

# Run the container
docker run -d \
  -p 8080:80 \
  --name vcard-app \
  -e APP_ENV=production \
  -e APP_KEY=your-app-key \
  -e DB_HOST=your-db-host \
  -e DB_DATABASE=your-database \
  -e DB_USERNAME=your-username \
  -e DB_PASSWORD=your-password \
  ertusharmodi/managehub-vcard:latest

# Visit http://localhost:8080
```

## Docker Images

### Main Dockerfile (Nginx + PHP-FPM)

- **Target**: `production`
- **Stack**: Nginx 1.25 + PHP 8.2-FPM
- **Features**:
    - OPcache enabled
    - Redis support
    - Queue worker via Supervisor
    - Laravel scheduler
    - Optimized for production performance

### Alternative Dockerfile.apache (Apache + PHP)

- **Target**: Single-stage Apache
- **Stack**: Apache 2.4 + PHP 8.2
- **Features**:
    - Simpler architecture
    - Queue worker via Supervisor
    - Laravel scheduler
    - Good for smaller deployments

## Build From Source

### Build Nginx version

```bash
docker build -t managehub-vcard:nginx -f Dockerfile --target production .
```

### Build Apache version

```bash
docker build -t managehub-vcard:apache -f Dockerfile.apache .
```

## Configuration Files

### PHP Settings

- `docker/php/php.ini` - Main PHP configuration
- `docker/php/opcache.ini` - OPcache optimization

### Nginx Configuration

- `docker/nginx/default.conf` - Nginx server block with Laravel routing

### Supervisor

- `docker/supervisor/supervisord.conf` - Nginx + PHP-FPM + Queue + Scheduler
- `docker/supervisor/apache-supervisord.conf` - Apache + Queue + Scheduler

## Environment Variables

Required variables:

```env
APP_NAME="ManageHub vCard"
APP_ENV=production
APP_KEY=base64:your-generated-key
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=vcard
DB_USERNAME=vcard_user
DB_PASSWORD=secure_password

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=redis
REDIS_PORT=6379
```

## Production Checklist

- [ ] Generate APP_KEY: `php artisan key:generate`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure proper database credentials
- [ ] Set up Redis for caching and sessions
- [ ] Configure mail settings (SMTP)
- [ ] Set proper file permissions on storage volumes
- [ ] Run migrations: `php artisan migrate --force`
- [ ] Link storage: `php artisan storage:link`
- [ ] Clear and cache configs:
    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    ```

## Volumes

### Important directories to persist:

- `./storage` - User uploads, logs, cache
- `./public/uploads` - Public uploaded files

### Docker Compose volumes:

- `db-data` - MySQL database files
- `redis-data` - Redis persistence

## Supervisor Services

The production image runs multiple services via Supervisor:

1. **nginx** - Web server (port 80)
2. **php-fpm** - PHP processor (port 9000)
3. **laravel-queue** - Background job processing
4. **laravel-schedule** - Cron scheduler (runs every minute)

View service status:

```bash
docker-compose exec app supervisorctl status
```

## Logs

View application logs:

```bash
# All logs
docker-compose logs -f app

# Laravel logs
docker-compose exec app tail -f storage/logs/laravel.log

# Queue worker logs
docker-compose exec app tail -f storage/logs/worker.log

# Scheduler logs
docker-compose exec app tail -f storage/logs/scheduler.log

# Nginx access logs
docker-compose exec app tail -f /var/log/nginx/access.log

# Nginx error logs
docker-compose exec app tail -f /var/log/nginx/error.log
```

## Performance Optimizations

The production image includes:

### PHP

- OPcache with aggressive caching
- 256M memory limit
- 20MB upload limit
- Optimized autoloader

### Nginx

- Gzip compression
- Static asset caching (1 year)
- Security headers
- FastCGI caching ready

### Laravel

- Config cached
- Routes cached
- Views cached
- Classmap authoritative

## Troubleshooting

### Container won't start

```bash
# Check logs
docker-compose logs app

# Check Supervisor status
docker-compose exec app supervisorctl status

# Restart services
docker-compose restart app
```

### Permission issues

```bash
# Fix storage permissions
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Clear Laravel caches

```bash
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear
```

### Database connection failed

- Verify DB_HOST matches your docker-compose service name
- Check database credentials in .env
- Ensure database container is running: `docker-compose ps`

## CI/CD with GitHub Actions

The repository includes `.github/workflows/docker-CI.yml` which:

1. Builds Docker image on push to `main`
2. Pushes to Docker Hub as `ertusharmodi/managehub-vcard:latest`
3. Tags images with commit SHA and branch names
4. Uses Docker layer caching for faster builds

### Required GitHub Secrets:

- `DOCKER_USERNAME` - Your Docker Hub username
- `DOCKER_PASSWORD` - Your Docker Hub password or access token

## Scaling

### Run multiple queue workers

Edit `docker/supervisor/supervisord.conf`:

```ini
[program:laravel-queue]
numprocs=3  # Change from 1 to 3
process_name=%(program_name)s_%(process_num)02d
```

### Use external Redis/MySQL

Update docker-compose.yml environment variables to point to external services and remove the redis/db services.

## Health Checks

Add to docker-compose.yml:

```yaml
services:
    app:
        healthcheck:
            test: ["CMD", "curl", "-f", "http://localhost/health"]
            interval: 30s
            timeout: 10s
            retries: 3
            start_period: 40s
```

Create health check route in `routes/web.php`:

```php
Route::get('/health', function () {
    return response()->json(['status' => 'healthy']);
});
```

## Support

For issues, check:

1. Application logs in `storage/logs/`
2. Docker logs: `docker-compose logs`
3. Supervisor status: `supervisorctl status`
4. Database connectivity: `php artisan tinker` → `DB::connection()->getPdo();`
