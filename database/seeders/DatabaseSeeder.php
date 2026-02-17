<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WebsitePage;
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

        WebsitePage::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Home',
                'hero_title' => 'vCards,',
                'hero_title_highlight' => 'Reimagined',
                'hero_subtitle' => 'ManageHub helps businesses, creators, and professionals share rich, interactive digital business cards — instantly, beautifully, and with purpose.',
                'header_cta' => [
                    'label' => 'Get Started',
                    'url' => '#',
                ],
                'hero_buttons' => [
                    ['label' => 'Create Your vCard →', 'url' => '#'],
                    ['label' => 'View Demo', 'url' => '#'],
                ],
                'categories' => [
                    'title' => 'vCards Built for Every',
                    'highlight' => 'Category',
                    'suffix' => '',
                    'subtitle' => 'Choose the perfect template — optimized for your role, industry, or use case.',
                    'items' => [
                        [
                            'title' => 'Business',
                            'description' => 'For companies, teams & departments — embed logos, team members, locations & more.',
                            'icon' => 'fas fa-building',
                            'icon_bg' => 'bg-blue-100',
                            'icon_color' => 'text-blue-600',
                        ],
                        [
                            'title' => 'Professional',
                            'description' => 'Lawyers, doctors, consultants — highlight credentials, certifications & contact workflows.',
                            'icon' => 'fas fa-user-tie',
                            'icon_bg' => 'bg-emerald-100',
                            'icon_color' => 'text-emerald-600',
                        ],
                        [
                            'title' => 'Creative',
                            'description' => 'Designers, photographers, musicians — showcase portfolios, social links & booking buttons.',
                            'icon' => 'fas fa-paint-brush',
                            'icon_bg' => 'bg-purple-100',
                            'icon_color' => 'text-purple-600',
                        ],
                        [
                            'title' => 'Personal',
                            'description' => 'Friends, family, networking — simple, warm, privacy-aware sharing for life moments.',
                            'icon' => 'fas fa-users',
                            'icon_bg' => 'bg-amber-100',
                            'icon_color' => 'text-amber-600',
                        ],
                        [
                            'title' => 'Event',
                            'description' => 'Conferences, weddings, meetups — include RSVP, maps, schedules & guest lists.',
                            'icon' => 'fas fa-calendar-day',
                            'icon_bg' => 'bg-cyan-100',
                            'icon_color' => 'text-cyan-600',
                        ],
                    ],
                ],
                'vcard_preview' => [
                    'heading' => 'A vCard That',
                    'highlight' => 'Does More',
                    'suffix' => '',
                    'subheading' => 'Interactive. Trackable. Brand-aligned. One link. Infinite updates.',
                    'initials' => 'A',
                    'name' => 'Alex Morgan',
                    'role' => 'Senior Product Designer',
                    'company' => 'TechNova Labs',
                    'location' => 'San Francisco',
                    'email' => 'alex@technova.dev',
                    'phone' => '+1 (415) 555-0199',
                    'linkedin_label' => 'linkedin.com/in/alexmorgan',
                    'linkedin_url' => '#',
                    'dribbble_label' => 'dribbble.com/alexmorgan',
                    'dribbble_url' => '#',
                ],
                'how_it_works' => [
                    'title' => 'How Manage',
                    'highlight' => 'Hub',
                    'suffix' => ' Works',
                    'subtitle' => 'Three simple steps — no tech skills needed.',
                    'steps' => [
                        [
                            'number' => '1',
                            'title' => 'Create',
                            'description' => 'Pick a category, customize design, add contact info, links & CTAs.',
                            'badge_bg' => 'bg-blue-100',
                            'badge_text' => 'text-blue-700',
                        ],
                        [
                            'number' => '2',
                            'title' => 'Share',
                            'description' => 'Get a unique short link (e.g. managehub.io/v/yourname) — share anywhere.',
                            'badge_bg' => 'bg-emerald-100',
                            'badge_text' => 'text-emerald-700',
                        ],
                        [
                            'number' => '3',
                            'title' => 'Track & Update',
                            'description' => 'See who viewed your card, when & where — update info anytime. No re-sharing!',
                            'badge_bg' => 'bg-purple-100',
                            'badge_text' => 'text-purple-700',
                        ],
                    ],
                ],
                'cta_section' => [
                    'title' => 'Ready to Elevate Your Digital Presence?',
                    'subtitle' => 'Join thousands of professionals already using ManageHub to make every connection count.',
                    'primary_label' => 'Start Free Trial',
                    'primary_url' => '#',
                    'secondary_label' => 'Schedule a Demo',
                    'secondary_url' => '#',
                ],
                'footer_about' => 'The smartest way to share, track, and grow your professional network — one vCard at a time.',
                'footer_links' => [
                    'product' => [
                        ['label' => 'Features', 'url' => '#'],
                        ['label' => 'Templates', 'url' => '#'],
                        ['label' => 'Analytics', 'url' => '#'],
                        ['label' => 'Integrations', 'url' => '#'],
                    ],
                    'resources' => [
                        ['label' => 'Blog', 'url' => '#'],
                        ['label' => 'Help Center', 'url' => '#'],
                        ['label' => 'API Docs', 'url' => '#'],
                        ['label' => 'Status', 'url' => '#'],
                    ],
                ],
            ]
        );

    }
}
