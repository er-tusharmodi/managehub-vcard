<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Mongo\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles in MongoDB
        Role::query()->updateOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            ['permissions' => []]
        );
        
        Role::query()->updateOrCreate(
            ['name' => 'manager', 'guard_name' => 'web'],
            ['permissions' => []]
        );

        // Create admin user
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@managehub.test'],
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'roles' => ['admin'],
                'permissions' => [],
            ]
        );

        $this->command->info('Admin user created: admin@managehub.test / admin123');

        $this->call([
            WebsiteCmsFromHtmlSeeder::class,
            TemplateSeeder::class,
        ]);
    }
}
