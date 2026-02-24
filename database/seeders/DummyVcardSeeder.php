<?php

namespace Database\Seeders;

use App\Helpers\UsernameGenerator;
use App\Models\User;
use App\Models\Vcard;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DummyVcardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'Tushar Modi', 'Rajesh Kumar', 'Priya Singh', 'Amit Patel', 'Neha Sharma',
            'Rahul Verma', 'Anjali Gupta', 'Vikram Singh', 'Deepika Roy', 'Arjun Malhotra',
            'Sanya Kapoor', 'Rohan Chopra', 'Zara Khan', 'Nikhil Reddy', 'Isha Nair'
        ];

        $companies = [
            'Tech Solutions', 'Digital Agency', 'Marketing Pro', 'Design Studio', 'Cloud Services',
            'Mobile Apps', 'Web Development', 'Data Analytics', 'Consulting Group', 'Software House',
            'IT Services', 'E-commerce Plus', 'Digital Marketing', 'AI Innovations', 'Startup Hub'
        ];

        $templates = ['minimal', 'professional', 'creative', 'modern', 'elegant'];

        $admin = User::where('email', 'admin@managehub.test')->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin',
                'email' => 'admin@managehub.test',
                'password' => Hash::make('password'),
            ]);
        }

        for ($i = 1; $i <= 15; $i++) {
            $name = $names[$i - 1];
            $subdomain = 'vcard-' . $i;
            $email = 'client' . $i . '@example.com';

            // Create or fetch user
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'username' => UsernameGenerator::generateFromSubdomain($subdomain),
                    'password' => Hash::make('password123'),
                ]
            );

            // Create vCard
            Vcard::create([
                'user_id' => $user->id,
                'subdomain' => $subdomain,
                'template_key' => $templates[($i - 1) % count($templates)],
                'client_name' => $name,
                'client_email' => $email,
                'client_phone' => '+91 ' . rand(7000000000, 9999999999),
                'client_address' => 'City ' . chr(64 + $i) . ', India',
                'status' => 'active',
                'subscription_status' => $i % 3 === 0 ? 'inactive' : 'active',
                'subscription_started_at' => now()->subDays(rand(1, 60)),
                'subscription_expires_at' => now()->addDays(rand(30, 365)),
                'created_by' => $admin->id,
            ]);

            echo "✓ Created vCard: $subdomain for $name" . PHP_EOL;
        }

        echo PHP_EOL . "✅ 15 dummy vCards created successfully!" . PHP_EOL;
    }
}
