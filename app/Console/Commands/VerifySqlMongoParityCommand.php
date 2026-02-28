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

class VerifySqlMongoParityCommand extends Command
{
    protected $signature = 'mongo:verify-sql-parity';

    protected $description = 'Compare SQL and MongoDB record counts for website and auth data';

    public function handle(): int
    {
        $userPermissions = User::query()
            ->get()
            ->flatMap(fn ($user) => $user->permissions ?? [])
            ->filter()
            ->unique();

        $userRoles = User::query()
            ->get()
            ->flatMap(fn ($user) => $user->roles ?? [])
            ->filter()
            ->unique();

        $mongoMatchedRoles = MongoRole::query()
            ->whereIn('name', $userRoles->values()->all())
            ->count();

        $rows = [
            $this->row('website_settings', WebsiteSetting::query()->count(), MongoWebsiteSetting::query()->count()),
            $this->row('website_pages', WebsitePage::query()->count(), MongoWebsitePage::query()->count()),
            $this->row('templates', DB::connection('mysql')->table('templates')->count(), DB::connection('mongodb')->table('templates')->count()),
            $this->row('vcards', DB::connection('mysql')->table('vcards')->count(), DB::connection('mongodb')->table('vcards')->count()),
            $this->row('vcard_orders', DB::connection('mysql')->table('vcard_orders')->count(), DB::connection('mongodb')->table('vcard_orders')->count()),
            $this->row('vcard_bookings', DB::connection('mysql')->table('vcard_bookings')->count(), DB::connection('mongodb')->table('vcard_bookings')->count()),
            $this->row('vcard_enquiries', DB::connection('mysql')->table('vcard_enquiries')->count(), DB::connection('mongodb')->table('vcard_enquiries')->count()),
            $this->row('vcard_contacts', DB::connection('mysql')->table('vcard_contacts')->count(), DB::connection('mongodb')->table('vcard_contacts')->count()),
            $this->row('vcard_visits', DB::connection('mysql')->table('vcard_visits')->count(), DB::connection('mongodb')->table('vcard_visits')->count()),
            $this->row('permissions', $userPermissions->count(), MongoPermission::query()->count()),
            $this->row('roles', $userRoles->count(), $mongoMatchedRoles),
            $this->row('users', User::query()->count(), UserAccount::query()->count()),
        ];

        $this->table(['Entity', 'SQL', 'Mongo', 'Status'], $rows);

        $hasMismatch = collect($rows)->contains(fn (array $row) => $row[3] !== 'OK');

        if ($hasMismatch) {
            $this->warn('Parity check found mismatches. Run mongo:migrate-sql-data and verify again.');
            return self::FAILURE;
        }

        $this->info('Parity check passed for all entities.');
        return self::SUCCESS;
    }

    private function row(string $entity, int $sqlCount, int $mongoCount): array
    {
        return [
            $entity,
            $sqlCount,
            $mongoCount,
            $sqlCount === $mongoCount ? 'OK' : 'MISMATCH',
        ];
    }
}
