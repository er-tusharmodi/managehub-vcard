<?php

namespace App\Providers;

use App\Repositories\Contracts\SubmissionRepository;
use App\Repositories\Contracts\VcardContentRepository;
use App\Repositories\Contracts\WebsitePageRepository;
use App\Repositories\Contracts\WebsiteSettingRepository;
use App\Repositories\DualWrite\DualWriteSubmissionRepository;
use App\Repositories\DualWrite\DualWriteVcardContentRepository;
use App\Repositories\DualWrite\DualWriteWebsitePageRepository;
use App\Repositories\DualWrite\DualWriteWebsiteSettingRepository;
use App\Repositories\Mongo\MongoSubmissionRepository;
use App\Repositories\Mongo\MongoVcardContentRepository;
use App\Repositories\Mongo\MongoWebsitePageRepository;
use App\Repositories\Mongo\MongoWebsiteSettingRepository;
use App\Repositories\Sql\SqlSubmissionRepository;
use App\Repositories\Sql\SqlVcardContentRepository;
use App\Repositories\Sql\SqlWebsitePageRepository;
use App\Repositories\Sql\SqlWebsiteSettingRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(VcardContentRepository::class, function ($app) {
            $mode = (string) config('app.vcard_storage_mode', 'file_only');

            return match ($mode) {
                'mongo_only', 'mongo_preferred' => $app->make(MongoVcardContentRepository::class),
                'dual_write' => $app->make(DualWriteVcardContentRepository::class),
                default => $app->make(SqlVcardContentRepository::class),
            };
        });

        $this->app->bind(SubmissionRepository::class, function ($app) {
            $mode = (string) config('app.vcard_storage_mode', 'file_only');

            return match ($mode) {
                'mongo_only', 'mongo_preferred' => $app->make(MongoSubmissionRepository::class),
                'dual_write' => $app->make(DualWriteSubmissionRepository::class),
                default => $app->make(SqlSubmissionRepository::class),
            };
        });

        $this->app->bind(WebsitePageRepository::class, function ($app) {
            $mode = (string) config('app.vcard_storage_mode', 'file_only');

            return match ($mode) {
                'mongo_only', 'mongo_preferred' => $app->make(MongoWebsitePageRepository::class),
                'dual_write' => $app->make(DualWriteWebsitePageRepository::class),
                default => $app->make(SqlWebsitePageRepository::class),
            };
        });

        $this->app->bind(WebsiteSettingRepository::class, function ($app) {
            $mode = (string) config('app.vcard_storage_mode', 'file_only');

            return match ($mode) {
                'mongo_only', 'mongo_preferred' => $app->make(MongoWebsiteSettingRepository::class),
                'dual_write' => $app->make(DualWriteWebsiteSettingRepository::class),
                default => $app->make(SqlWebsiteSettingRepository::class),
            };
        });
    }
}
