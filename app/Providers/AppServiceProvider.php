<?php

namespace App\Providers;

use App\Models\Mongo\Role;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Share branding helper with all views
        \Illuminate\Support\Facades\View::share('branding', \App\Helpers\BrandingHelper::class);

        if (!app()->runningInConsole()) {
            try {
                foreach (['admin', 'super-admin', 'client'] as $roleName) {
                    Role::query()->firstOrCreate(
                        ['name' => $roleName],
                        ['guard_name' => 'web', 'permissions' => []]
                    );
                }
            } catch (\Throwable) {
                // Ignore bootstrap role creation failures to avoid blocking requests.
            }
        }
    }
}
