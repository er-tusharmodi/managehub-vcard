<?php

namespace App\Console\Commands;

use App\Models\Mongo\Permission as MongoPermission;
use App\Models\Mongo\Role as MongoRole;
use App\Models\Mongo\UserAccount;
use App\Models\Mongo\WebsitePage as MongoWebsitePage;
use App\Models\Mongo\WebsiteSetting as MongoWebsiteSetting;
use App\Models\User;
use App\Models\WebsitePage;
use App\Models\WebsiteSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateSqlToMongoCommand extends Command
{
    protected $signature = 'mongo:migrate-sql-data';

    protected $description = 'Migrate website and auth data from SQL tables to MongoDB collections';

    public function handle(): int
    {
        $this->info('Starting SQL -> Mongo migration for website/auth/vcard data...');

        $counts = [
            'website_settings' => $this->migrateWebsiteSettings(),
            'website_pages' => $this->migrateWebsitePages(),
            'templates' => $this->migrateTemplates(),
            'vcards' => $this->migrateVcards(),
            'vcard_orders' => $this->migrateVcardOrders(),
            'vcard_bookings' => $this->migrateVcardBookings(),
            'vcard_enquiries' => $this->migrateVcardEnquiries(),
            'vcard_contacts' => $this->migrateVcardContacts(),
            'vcard_visits' => $this->migrateVcardVisits(),
            'permissions' => $this->syncPermissionsFromUsers(),
            'roles' => $this->migrateRoles(),
            'users' => $this->migrateUsers(),
        ];

        $this->newLine();
        $this->info('Migration summary:');
        $this->table(
            ['Collection', 'Documents upserted'],
            collect($counts)->map(fn($count, $key) => [$key, $count])->values()->all()
        );

        return self::SUCCESS;
    }

    private function migrateWebsiteSettings(): int
    {
        $count = 0;

        WebsiteSetting::query()->chunk(200, function ($settings) use (&$count) {
            foreach ($settings as $setting) {
                MongoWebsiteSetting::query()->updateOrCreate(
                    ['key' => $setting->key],
                    ['value' => $setting->value]
                );
                $count++;
            }
        });

        return $count;
    }

    private function migrateWebsitePages(): int
    {
        $count = 0;

        $fillableKeys = [
            'slug',
            'title',
            'meta_title',
            'meta_description',
            'hero_title',
            'hero_title_highlight',
            'hero_subtitle',
            'header_cta',
            'hero_buttons',
            'hero_image_path',
            'categories',
            'vcard_preview',
            'how_it_works',
            'cta_section',
            'about_title',
            'about_body',
            'about_image_path',
            'services',
            'testimonials',
            'faqs',
            'footer_text',
            'footer_about',
            'footer_links',
            'data',
        ];

        WebsitePage::query()->chunk(50, function ($pages) use (&$count, $fillableKeys) {
            foreach ($pages as $page) {
                $payload = array_intersect_key($page->toArray(), array_flip($fillableKeys));

                MongoWebsitePage::query()->updateOrCreate(
                    ['slug' => $page->slug],
                    $payload
                );

                $count++;
            }
        });

        return $count;
    }

    private function migrateTemplates(): int
    {
        $count = 0;
        $sqlTemplates = DB::connection('mysql')->table('templates')->get();
        
        foreach ($sqlTemplates as $template) {
            $arr = (array) $template;
            unset($arr['id']);
            DB::connection('mongodb')->table('templates')->insert($arr);
            $count++;
        }

        return $count;
    }

    private function migrateVcards(): int
    {
        $count = 0;
        $sqlVcards = DB::connection('mysql')->table('vcards')->get();
        
        foreach ($sqlVcards as $vcard) {
            $arr = (array) $vcard;
            unset($arr['id']);
            DB::connection('mongodb')->table('vcards')->insert($arr);
            $count++;
        }

        return $count;
    }

    private function migrateVcardOrders(): int
    {
        $count = 0;
        $sqlOrders = DB::connection('mysql')->table('vcard_orders')->get();
        
        foreach ($sqlOrders as $order) {
            $arr = (array) $order;
            unset($arr['id']);
            DB::connection('mongodb')->table('vcard_orders')->insert($arr);
            $count++;
        }

        return $count;
    }

    private function migrateVcardBookings(): int
    {
        $count = 0;
        $sqlBookings = DB::connection('mysql')->table('vcard_bookings')->get();
        
        foreach ($sqlBookings as $booking) {
            $arr = (array) $booking;
            unset($arr['id']);
            DB::connection('mongodb')->table('vcard_bookings')->insert($arr);
            $count++;
        }

        return $count;
    }

    private function migrateVcardEnquiries(): int
    {
        $count = 0;
        $sqlEnquiries = DB::connection('mysql')->table('vcard_enquiries')->get();
        
        foreach ($sqlEnquiries as $enquiry) {
            $arr = (array) $enquiry;
            unset($arr['id']);
            DB::connection('mongodb')->table('vcard_enquiries')->insert($arr);
            $count++;
        }

        return $count;
    }

    private function migrateVcardContacts(): int
    {
        $count = 0;
        $sqlContacts = DB::connection('mysql')->table('vcard_contacts')->get();
        
        foreach ($sqlContacts as $contact) {
            $arr = (array) $contact;
            unset($arr['id']);
            DB::connection('mongodb')->table('vcard_contacts')->insert($arr);
            $count++;
        }

        return $count;
    }

    private function migrateVcardVisits(): int
    {
        $count = 0;
        $sqlVisits = DB::connection('mysql')->table('vcard_visits')->get();
        
        foreach ($sqlVisits as $visit) {
            $arr = (array) $visit;
            unset($arr['id']);
            DB::connection('mongodb')->table('vcard_visits')->insert($arr);
            $count++;
        }

        return $count;
    }

    private function syncPermissionsFromUsers(): int
    {
        $permissions = User::query()
            ->get()
            ->flatMap(fn ($user) => $user->permissions ?? [])
            ->filter()
            ->unique()
            ->values();

        foreach ($permissions as $permission) {
            MongoPermission::query()->updateOrCreate(
                ['name' => (string) $permission, 'guard_name' => 'web'],
                ['legacy_permission_id' => null]
            );
        }

        return $permissions->count();
    }

    private function migrateRoles(): int
    {
        $roles = User::query()
            ->get()
            ->flatMap(fn ($user) => $user->roles ?? [])
            ->filter()
            ->unique()
            ->values();

        foreach ($roles as $role) {
            MongoRole::query()->updateOrCreate(
                ['name' => (string) $role, 'guard_name' => 'web'],
                [
                    'legacy_role_id' => null,
                    'permissions' => [],
                ]
            );
        }

        return $roles->count();
    }

    private function migrateUsers(): int
    {
        $count = 0;

        User::query()->chunk(200, function ($users) use (&$count) {
            foreach ($users as $user) {
                UserAccount::query()->updateOrCreate(
                    ['email' => $user->email],
                    [
                        'legacy_user_id' => $user->id,
                        'name' => $user->name,
                        'username' => $user->username,
                        'profile_photo_path' => $user->profile_photo_path,
                        'password' => $user->password,
                        'roles' => $user->getRoleNames()->values()->all(),
                        'permissions' => $user->getAllPermissions()->pluck('name')->values()->all(),
                        'email_verified_at' => $user->email_verified_at,
                        'remember_token' => $user->remember_token,
                    ]
                );
                $count++;
            }
        });

        return $count;
    }
}
