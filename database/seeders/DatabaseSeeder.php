<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
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
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'manager']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@managehub.test'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin123'),
            ]
        );

        $admin->syncRoles([$adminRole->name]);

        $this->call(WebsiteCmsFromHtmlSeeder::class);
    }
}
