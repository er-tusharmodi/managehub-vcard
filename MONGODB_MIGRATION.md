# MongoDB Migration Documentation

## Overview

This Laravel application has been fully migrated from MySQL to MongoDB as the primary database. All models, authentication, and data storage now use MongoDB.

## Environment Configuration

### Required Environment Variables

```env
DB_CONNECTION=mongodb
DB_HOST=127.0.0.1
DB_PORT=27017
DB_DATABASE=vcard
DB_USERNAME=
DB_PASSWORD=

CACHE_STORE=file
QUEUE_CONNECTION=sync
VCARD_STORAGE_MODE=mongo_only
```

### Docker MongoDB Setup

```bash
# Start MongoDB container
docker run -d \
  --name mongo_vcard_host \
  -p 27017:27017 \
  -v mongo_vcard_data:/data/db \
  mongo:latest

# Access MongoDB shell
docker exec -it mongo_vcard_host mongosh
```

## MongoDB Collections

The following collections are used:

| Collection       | Purpose                          | Model                       |
| ---------------- | -------------------------------- | --------------------------- |
| users            | User accounts and authentication | App\Models\User             |
| roles            | Role definitions                 | App\Models\Mongo\Role       |
| permissions      | Permission definitions           | App\Models\Mongo\Permission |
| vcards           | Virtual business cards           | App\Models\Vcard            |
| vcard_orders     | vCard purchase orders            | App\Models\VcardOrder       |
| vcard_bookings   | Customer bookings                | App\Models\VcardBooking     |
| vcard_enquiries  | Customer enquiries               | App\Models\VcardEnquiry     |
| vcard_contacts   | Customer contacts                | App\Models\VcardContact     |
| vcard_visits     | vCard visit tracking             | App\Models\VcardVisit       |
| templates        | vCard design templates           | App\Models\Template         |
| website_pages    | CMS page content                 | App\Models\WebsitePage      |
| website_settings | Application settings             | App\Models\WebsiteSetting   |

## Authentication System

### User Model

The User model extends `MongoDB\Laravel\Auth\User` and implements custom role/permission methods:

```php
// Check if user has role(s)
$user->hasRole('admin');
$user->hasRole(['admin', 'super-admin']);

// Assign role(s) to user
$user->assignRole('client');
$user->assignRole(['admin', 'client']);

// Sync roles (replace existing)
$user->syncRoles(['admin', 'super-admin']);

// Get user's roles
$roles = $user->getRoleNames(); // Returns array

// Get user's permissions
$permissions = $user->getAllPermissions(); // Returns array
```

### Custom Middleware

Three middleware classes replace Spatie Permission package:

1. **RoleMiddleware** - Check user has specific role(s)

    ```php
    Route::middleware('role:admin')->group(function () {
        // Admin routes
    });
    ```

2. **PermissionMiddleware** - Check user has specific permission(s)

    ```php
    Route::middleware('permission:manage-vcards')->group(function () {
        // Protected routes
    });
    ```

3. **RoleOrPermissionMiddleware** - Check user has role OR permission
    ```php
    Route::middleware('role_or_permission:admin|manage-vcards')->group(function () {
        // Routes accessible by admin OR users with manage-vcards permission
    });
    ```

## Data Migration

### Migration Command

Migrate all data from MySQL to MongoDB:

```bash
php artisan mongo:migrate-sql-data
```

This command migrates:

- Website settings (14 records)
- Website pages (1+ records)
- Templates (10 records)
- vcards and related submissions
- User accounts, roles, and permissions

### Parity Verification

Verify SQL and MongoDB data consistency:

```bash
php artisan mongo:verify-sql-parity
```

Expected output:

```
+------------------+-----+-------+--------+
| Entity           | SQL | Mongo | Status |
+------------------+-----+-------+--------+
| website_settings | 14  | 14    | OK     |
| website_pages    | 1   | 1     | OK     |
| templates        | 10  | 10    | OK     |
| vcards           | 0   | 0     | OK     |
| vcard_orders     | 0   | 0     | OK     |
| vcard_bookings   | 0   | 0     | OK     |
| vcard_enquiries  | 0   | 0     | OK     |
| vcard_contacts   | 0   | 0     | OK     |
| vcard_visits     | 0   | 0     | OK     |
| permissions      | 0   | 0     | OK     |
| roles            | 2   | 2     | OK     |
| users            | 2   | 2     | OK     |
+------------------+-----+-------+--------+
```

## Repository Pattern

### Storage Modes

The application supports three storage modes via `VCARD_STORAGE_MODE`:

1. **file_only** - Store submissions as JSON files only
2. **dual_write** - Write to both MySQL and MongoDB (migration phase)
3. **mongo_only** - Write only to MongoDB (current default)

### Repository Contracts

Three repository interfaces provide storage abstraction:

- `VcardSubmissionRepository` - vCard customer submissions
- `WebsitePageRepository` - CMS page data
- `WebsiteSettingRepository` - Application settings

### Implementations

Each repository has multiple implementations:

**VcardSubmissionRepository:**

- `FileSubmissionRepository` - File-based storage
- `SqlSubmissionRepository` - MySQL storage
- `MongoVcardSubmissionRepository` - MongoDB storage
- `DualWriteVcardSubmissionRepository` - Dual-write wrapper

**WebsitePageRepository:**

- `SqlWebsitePageRepository` - MySQL storage
- `MongoWebsitePageRepository` - MongoDB storage with SQL fallback
- `DualWriteWebsitePageRepository` - Dual-write wrapper

**WebsiteSettingRepository:**

- `SqlWebsiteSettingRepository` - MySQL storage
- `MongoWebsiteSettingRepository` - MongoDB storage with SQL fallback
- `DualWriteWebsiteSettingRepository` - Dual-write wrapper

### Service Binding

Repository implementations are bound in `RepositoryServiceProvider` based on `VCARD_STORAGE_MODE`.

## Model Configuration

### MongoDB Eloquent Models

All models extend `MongoDB\Laravel\Eloquent\Model` and use `$table` property (not `$collection`):

```php
use MongoDB\Laravel\Eloquent\Model;

class Vcard extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'vcards';

    // Relationships work the same
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

### Important Notes

- MongoDB Laravel package uses `$table` property, not `$collection`
- Auth User model MUST extend `MongoDB\Laravel\Auth\User`
- Relationships (belongsTo, hasMany) work normally
- Casts (array, decimal, datetime) are supported

## Removed Dependencies

### Spatie Permission Package

The `spatie/laravel-permission` package has been completely removed:

```bash
composer remove spatie/laravel-permission --ignore-platform-req=ext-mongodb
```

Replaced with custom array-based role/permission system:

- User roles stored in `users.roles` array field
- User permissions stored in `users.permissions` array field
- Custom middleware checks array membership

## PHP Extensions

### Required Extension

MongoDB PHP extension must be installed:

```bash
# Check current version
php -m | grep mongodb

# Install/upgrade (macOS with Homebrew)
pecl install mongodb

# Install/upgrade (Ubuntu/Debian)
sudo apt-get install php-mongodb

# Install/upgrade (Docker)
RUN pecl install mongodb && docker-php-ext-enable mongodb
```

**Note:** Current local environment has `ext-mongodb 1.20.0`, but Laravel MongoDB package requires `^1.21`. Use `--ignore-platform-req=ext-mongodb` for composer operations until upgrading.

## Testing

### Verify MongoDB Connection

```bash
php artisan about | grep -A5 "Database"
```

Expected output:

```
Database
  Default: mongodb
  Connections: mysql, mongodb
```

### Verify User Authentication

```bash
php artisan tinker
```

```php
$user = App\Models\User::first();
echo $user->email;
echo $user->hasRole('admin') ? 'YES' : 'NO';
print_r($user->getRoleNames());
```

### Verify Data Access

```bash
php artisan tinker
```

```php
echo 'Users: ' . App\Models\User::count();
echo 'Templates: ' . App\Models\Template::count();
echo 'Website Pages: ' . App\Models\WebsitePage::count();
```

## Troubleshooting

### Collection Not Found

If models return 0 records, check collection name:

```bash
php artisan tinker
```

```php
// List all collections
$collections = DB::connection('mongodb')->getDatabase()->listCollectionNames();
foreach ($collections as $name) {
    $count = DB::connection('mongodb')->table($name)->count();
    echo "$name: $count\n";
}
```

### Legacy Collection Migration

If data exists in legacy collections (e.g., `user_accounts` instead of `users`):

```bash
php artisan tinker
```

```php
// Migrate from legacy collection
$legacy = DB::connection('mongodb')->table('user_accounts')->get();
foreach ($legacy as $doc) {
    $arr = (array) $doc;
    unset($arr['_id']);
    DB::connection('mongodb')->table('users')->updateOrInsert(
        ['email' => $arr['email'] ?? null],
        $arr
    );
}
```

### Role/Permission Errors

If role middleware fails, verify user has roles array:

```bash
php artisan tinker
```

```php
$user = App\Models\User::first();
print_r($user->roles);

// Assign roles if missing
$user->syncRoles(['admin']);
```

## Production Deployment

### Pre-Deployment Checklist

- [ ] Upgrade `ext-mongodb` to 1.21+ on production server
- [ ] Configure MongoDB connection in production `.env`
- [ ] Run data migration: `php artisan mongo:migrate-sql-data`
- [ ] Verify parity: `php artisan mongo:verify-sql-parity`
- [ ] Test authentication flow (login/logout)
- [ ] Test vCard creation and editing
- [ ] Test CMS page editing
- [ ] Verify customer submission storage

### Cache Clearing

After deployment:

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### Rollback Plan

If rollback to MySQL is needed:

1. Change `.env`:

    ```env
    DB_CONNECTION=mysql
    VCARD_STORAGE_MODE=dual_write
    ```

2. Clear config:

    ```bash
    php artisan config:clear
    ```

3. MongoDB data remains available for future migration

## Performance Considerations

### Indexing

Create indexes for frequently queried fields:

```javascript
// In MongoDB shell
use vcard;

// User lookups
db.users.createIndex({ email: 1 });
db.users.createIndex({ username: 1 });

// vCard lookups
db.vcards.createIndex({ user_id: 1 });
db.vcards.createIndex({ slug: 1 });

// Template lookups
db.templates.createIndex({ slug: 1 });
db.templates.createIndex({ is_visible: 1, order_position: 1 });

// CMS lookups
db.website_pages.createIndex({ slug: 1 });
db.website_settings.createIndex({ key: 1 });
```

### Query Optimization

- Use `select()` to limit returned fields
- Use `chunk()` for large datasets
- Leverage MongoDB aggregation pipeline for complex queries

## Support

For issues or questions about the MongoDB migration:

1. Check this documentation
2. Review command output: `php artisan mongo:verify-sql-parity`
3. Check Laravel logs: `storage/logs/laravel.log`
4. Verify MongoDB connection: `docker exec -it mongo_vcard_host mongosh`
