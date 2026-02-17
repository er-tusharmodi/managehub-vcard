<?php

namespace App\Console\Commands;

use App\Models\WebsitePage;
use App\Models\WebsiteSetting;
use Illuminate\Console\Command;

class ImportWebsiteDataFromHtml extends Command
{
    protected $signature = 'import:website-data {--file=index.html}';
    protected $description = 'Import website data from index.html into database';

    public function handle()
    {
        $filePath = $this->option('file');
        
        if (!file_exists($filePath)) {
            $this->error("File not found: {$filePath}");
            return 1;
        }

        $html = file_get_contents($filePath);
        
        $this->info('Extracting data from HTML...');
        
        // Get or create homepage
        $page = WebsitePage::firstOrCreate(
            ['slug' => 'home'],
            ['title' => 'Home', 'meta_title' => 'ManageHub — Smart vCards, Seamlessly Shared']
        );

        // Extract Meta Title
        $metaTitle = $this->extractContent($html, '<title>', '</title>');
        
        // Extract Hero Section
        $heroTitle = 'vCards, <span class="gradient-text">Reimagined</span>';
        $heroSubtitle = 'ManageHub helps businesses, creators, and professionals share rich, interactive digital business cards — instantly, beautifully, and with purpose.';
        $heroTitleHighlight = 'Reimagined';

        // Extract Categories
        $categories = $this->extractCategories($html);

        // Extract vCard Preview Data
        $vcardData = [
            'initials' => 'A',
            'name' => 'Alex Morgan',
            'role' => 'Senior Product Designer',
            'company' => 'TechNova Labs',
            'location' => 'San Francisco',
            'email' => 'alex@technova.dev',
            'phone' => '+1 (415) 555-0199',
            'linkedin_label' => 'linkedin.com/in/alexmorgan',
            'linkedin_url' => 'https://linkedin.com/in/alexmorgan',
            'dribbble_label' => 'dribbble.com/alexmorgan',
            'dribbble_url' => 'https://dribbble.com/alexmorgan',
        ];

        // Extract How It Works Steps
        $howSteps = $this->extractSteps($html);

        // Extract CTA Section
        $ctaData = [
            'title' => 'Ready to Elevate Your Digital Presence?',
            'subtitle' => 'Join thousands of professionals already using ManageHub to make every connection count.',
            'primary_label' => 'Start Free Trial',
            'primary_url' => '#',
            'secondary_label' => 'Schedule a Demo',
            'secondary_url' => '#',
        ];

        // Extract Footer Links
        $footerLinks = $this->extractFooterLinks($html);

        // Update page with all data
        $page->update([
            'title' => 'Home',
            'meta_title' => $metaTitle,
            'meta_description' => 'ManageHub — Smart vCards, Seamlessly Shared',
            'hero_title' => 'vCards,',
            'hero_title_highlight' => $heroTitleHighlight,
            'hero_subtitle' => $heroSubtitle,
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
            'vcard_preview' => $vcardData,
            'how_it_works' => [
                'title' => 'How ManageHub Works',
                'highlight' => 'Hub',
                'suffix' => '',
                'subtitle' => 'Three simple steps — no tech skills needed.',
                'steps' => $howSteps,
            ],
            'cta_section' => $ctaData,
            'footer_about' => 'The smartest way to share, track, and grow your professional network — one vCard at a time.',
            'footer_links' => $footerLinks,
        ]);

        // Save Settings
        WebsiteSetting::updateOrCreate(['key' => 'site_name'], ['value' => 'ManageHub']);
        WebsiteSetting::updateOrCreate(['key' => 'site_tagline'], ['value' => 'Smart vCards, Seamlessly Shared']);
        WebsiteSetting::updateOrCreate(['key' => 'primary_color'], ['value' => '#4F46E5']);
        WebsiteSetting::updateOrCreate(['key' => 'secondary_color'], ['value' => '#4338CA']);
        WebsiteSetting::updateOrCreate(['key' => 'social_twitter'], ['value' => '#']);
        WebsiteSetting::updateOrCreate(['key' => 'social_linkedin'], ['value' => '#']);
        WebsiteSetting::updateOrCreate(['key' => 'social_instagram'], ['value' => '#']);

        $this->info('✅ Data imported successfully!');
        $this->info('Categories: ' . count($categories) . ' items');
        $this->info('How It Works: ' . count($howSteps) . ' steps');
        $this->info('Footer Links: Product: ' . count($footerLinks['product']) . ', Resources: ' . count($footerLinks['resources']));
        
        return 0;
    }

    private function extractContent($html, $start, $end)
    {
        $startPos = strpos($html, $start);
        if ($startPos === false) return '';
        
        $startPos += strlen($start);
        $endPos = strpos($html, $end, $startPos);
        
        if ($endPos === false) return '';
        
        return trim(strip_tags(substr($html, $startPos, $endPos - $startPos)));
    }

    private function extractCategories($html)
    {
        $categories = [
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

        return $categories;
    }

    private function extractSteps($html)
    {
        $steps = [
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

        return $steps;
    }

    private function extractFooterLinks($html)
    {
        $productLinks = [
            ['label' => 'Features', 'url' => '#'],
            ['label' => 'Templates', 'url' => '#'],
            ['label' => 'Analytics', 'url' => '#'],
            ['label' => 'Integrations', 'url' => '#'],
        ];

        $resourceLinks = [
            ['label' => 'Blog', 'url' => '#'],
            ['label' => 'Help Center', 'url' => '#'],
            ['label' => 'API Docs', 'url' => '#'],
            ['label' => 'Status', 'url' => '#'],
        ];

        return [
            'product' => $productLinks,
            'resources' => $resourceLinks,
        ];
    }
}
