<?php

namespace Database\Seeders;

use App\Models\WebsitePage;
use App\Models\WebsiteSetting;
use Illuminate\Database\Seeder;

class WebsiteCmsFromHtmlSeeder extends Seeder
{
    public function run(): void
    {
        $html = '';

        $page = WebsitePage::firstOrCreate(
            ['slug' => 'home'],
            ['title' => 'Home', 'meta_title' => 'ManageHub — Smart vCards, Seamlessly Shared']
        );

        $metaTitle = 'ManageHub — Smart vCards, Seamlessly Shared';
        $categories = $this->extractCategories($html);
        $howSteps = $this->extractSteps($html);
        $footerLinks = $this->extractFooterLinks($html);

        $cmsData = [
            'hero_title' => 'vCards,',
            'hero_title_highlight' => 'Reimagined',
            'hero_subtitle' => 'ManageHub helps businesses, creators, and professionals share rich, interactive digital business cards — instantly, beautifully, and with purpose.',
            'hero_image_path' => '',
            'hero_buttons' => [
                [
                    'label' => 'Create Your vCard →',
                    'url' => '#',
                ],
                [
                    'label' => 'View Demo',
                    'url' => '#',
                ],
            ],
            'categories' => [
                'title' => 'vCards Built for Every',
                'highlight' => 'Category',
                'subtitle' => 'Choose the perfect template — optimized for your role, industry, or use case.',
                'items' => $categories,
            ],
            'vcard_previews' => [
                [
                    'title' => 'Alex Morgan',
                    'category' => 'Designer',
                    'preview_file' => '',
                ],
                [
                    'title' => 'Jordan Hayes',
                    'category' => 'Developer',
                    'preview_file' => '',
                ],
                [
                    'title' => 'Casey Wilson',
                    'category' => 'Business',
                    'preview_file' => '',
                ],
            ],
            'how_it_works' => [
                'title' => 'How ManageHub Works',
                'highlight' => 'Hub',
                'suffix' => '',
                'subtitle' => 'Three simple steps — no tech skills needed.',
                'steps' => $howSteps,
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
            'footer_links' => $footerLinks,
            'branding' => [
                'logo_url' => '',
                'favicon_url' => '',
                'primary_color' => '#4F46E5',
                'secondary_color' => '#4338CA',
            ],
            'social_links' => [
                ['platform' => 'Twitter', 'url' => '#'],
                ['platform' => 'LinkedIn', 'url' => '#'],
                ['platform' => 'Instagram', 'url' => '#'],
            ],
            'seo' => [
                'meta_keywords' => '',
                'canonical_url' => '',
            ],
        ];

        $page->update([
            'title' => 'Home',
            'meta_title' => $metaTitle,
            'meta_description' => 'ManageHub — Smart vCards, Seamlessly Shared',
            'hero_title' => 'vCards,',
            'hero_title_highlight' => 'Reimagined',
            'hero_subtitle' => 'ManageHub helps businesses, creators, and professionals share rich, interactive digital business cards — instantly, beautifully, and with purpose.',
            'hero_image_path' => '',
            'header_cta' => [
                'label' => 'Get Started',
                'url' => '#',
            ],
            'hero_buttons' => [
                [
                    'label' => 'Create Your vCard →',
                    'url' => '#',
                ],
                [
                    'label' => 'View Demo',
                    'url' => '#',
                ],
            ],
            'categories' => [
                'title' => 'vCards Built for Every',
                'highlight' => 'Category',
                'subtitle' => 'Choose the perfect template — optimized for your role, industry, or use case.',
                'items' => $categories,
            ],
            'vcard_previews' => [
                [
                    'title' => 'Alex Morgan',
                    'category' => 'Designer',
                    'preview_file' => '',
                ],
                [
                    'title' => 'Jordan Hayes',
                    'category' => 'Developer',
                    'preview_file' => '',
                ],
                [
                    'title' => 'Casey Wilson',
                    'category' => 'Business',
                    'preview_file' => '',
                ],
            ],
            'how_it_works' => [
                'title' => 'How ManageHub Works',
                'highlight' => 'Hub',
                'suffix' => '',
                'subtitle' => 'Three simple steps — no tech skills needed.',
                'steps' => $howSteps,
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
            'footer_links' => $footerLinks,
            'data' => $cmsData,
        ]);

        WebsiteSetting::updateOrCreate(['key' => 'site_name'], ['value' => 'ManageHub']);
        WebsiteSetting::updateOrCreate(['key' => 'site_tagline'], ['value' => 'Smart vCards, Seamlessly Shared']);
        WebsiteSetting::updateOrCreate(['key' => 'primary_color'], ['value' => '#4F46E5']);
        WebsiteSetting::updateOrCreate(['key' => 'secondary_color'], ['value' => '#4338CA']);
        WebsiteSetting::updateOrCreate(['key' => 'social_twitter'], ['value' => '#']);
        WebsiteSetting::updateOrCreate(['key' => 'social_linkedin'], ['value' => '#']);
        WebsiteSetting::updateOrCreate(['key' => 'social_instagram'], ['value' => '#']);

        $this->command?->info('Website CMS data seeded from embedded defaults');
    }

    private function extractContent(string $html, string $start, string $end): string
    {
        $startPos = strpos($html, $start);
        if ($startPos === false) {
            return '';
        }

        $startPos += strlen($start);
        $endPos = strpos($html, $end, $startPos);

        if ($endPos === false) {
            return '';
        }

        return trim(strip_tags(substr($html, $startPos, $endPos - $startPos)));
    }

    private function extractCategories(string $html): array
    {
        return [
            [
                'title' => 'Business',
                'description' => 'For companies, teams & departments — embed logos, team members, locations & more.',
                'icon' => 'fas fa-building',
                'icon_bg' => '#dbeafe',
                'icon_color' => '#0369a1',
            ],
            [
                'title' => 'Professional',
                'description' => 'Lawyers, doctors, consultants — highlight credentials, certifications & contact workflows.',
                'icon' => 'fas fa-user-tie',
                'icon_bg' => '#d1fae5',
                'icon_color' => '#047857',
            ],
            [
                'title' => 'Creative',
                'description' => 'Designers, photographers, musicians — showcase portfolios, social links & booking buttons.',
                'icon' => 'fas fa-paint-brush',
                'icon_bg' => '#e9d5ff',
                'icon_color' => '#7c3aed',
            ],
            [
                'title' => 'Personal',
                'description' => 'Friends, family, networking — simple, warm, privacy-aware sharing for life moments.',
                'icon' => 'fas fa-users',
                'icon_bg' => '#fef3c7',
                'icon_color' => '#d97706',
            ],
            [
                'title' => 'Event',
                'description' => 'Conferences, weddings, meetups — include RSVP, maps, schedules & guest lists.',
                'icon' => 'fas fa-calendar-day',
                'icon_bg' => '#cffafe',
                'icon_color' => '#0891b2',
            ],
        ];
    }

    private function extractSteps(string $html): array
    {
        return [
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
        ];
    }

    private function extractFooterLinks(string $html): array
    {
        return [
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
        ];
    }
}
