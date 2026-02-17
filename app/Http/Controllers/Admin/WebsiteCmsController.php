<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsitePage;
use App\Models\WebsiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebsiteCmsController extends Controller
{
    public function index(?WebsitePage $page = null): View
    {
        $page = $page ?? WebsitePage::where('slug', 'home')->first();
        if (!$page) {
            $page = WebsitePage::create([
                'slug' => 'home',
                'title' => 'Home',
            ]);
        }

        return view('admin.website-cms.index', [
            'page' => $page,
        ]);
    }

    public function showGeneral(?WebsitePage $page = null): View
    {
        $page = $page ?? WebsitePage::where('slug', 'home')->first();
        if (!$page) {
            $page = WebsitePage::create([
                'slug' => 'home',
                'title' => 'Home',
            ]);
        }

        return view('admin.website-cms.general', ['page' => $page]);
    }

    public function showBranding(?WebsitePage $page = null): View
    {
        $page = $page ?? WebsitePage::where('slug', 'home')->first();
        if (!$page) {
            $page = WebsitePage::create([
                'slug' => 'home',
                'title' => 'Home',
            ]);
        }

        return view('admin.website-cms.branding', [
            'page' => $page,
            'themeColors' => config('theme.default_colors'),
        ]);
    }

    public function showSocial(?WebsitePage $page = null): View
    {
        $page = $page ?? WebsitePage::where('slug', 'home')->first();
        if (!$page) {
            $page = WebsitePage::create([
                'slug' => 'home',
                'title' => 'Home',
            ]);
        }

        return view('admin.website-cms.social', ['page' => $page]);
    }

    public function showSeo(?WebsitePage $page = null): View
    {
        $page = $page ?? WebsitePage::where('slug', 'home')->first();
        if (!$page) {
            $page = WebsitePage::create([
                'slug' => 'home',
                'title' => 'Home',
            ]);
        }

        return view('admin.website-cms.seo', ['page' => $page]);
    }

    public function showHero(?WebsitePage $page = null): View
    {
        $page = $page ?? WebsitePage::where('slug', 'home')->first();
        if (!$page) {
            $page = WebsitePage::create([
                'slug' => 'home',
                'title' => 'Home',
            ]);
        }

        return view('admin.website-cms.hero', ['page' => $page]);
    }

    public function showCategories(?WebsitePage $page = null): View
    {
        $page = $page ?? WebsitePage::where('slug', 'home')->first();
        if (!$page) {
            $page = WebsitePage::create([
                'slug' => 'home',
                'title' => 'Home',
            ]);
        }

        return view('admin.website-cms.categories', [
            'page' => $page,
            'themeIcons' => config('theme.icons.categories'),
            'themeColors' => config('theme.default_colors'),
        ]);
    }

    public function showVcard(?WebsitePage $page = null): View
    {
        $page = $page ?? WebsitePage::where('slug', 'home')->first();
        if (!$page) {
            $page = WebsitePage::create([
                'slug' => 'home',
                'title' => 'Home',
            ]);
        }

        return view('admin.website-cms.vcard', ['page' => $page]);
    }

    public function showHowItWorks(?WebsitePage $page = null): View
    {
        $page = $page ?? WebsitePage::where('slug', 'home')->first();
        if (!$page) {
            $page = WebsitePage::create([
                'slug' => 'home',
                'title' => 'Home',
            ]);
        }

        return view('admin.website-cms.how-it-works', [
            'page' => $page,
            'themeColors' => config('theme.default_colors'),
        ]);
    }

    public function showCta(?WebsitePage $page = null): View
    {
        $page = $page ?? WebsitePage::where('slug', 'home')->first();
        if (!$page) {
            $page = WebsitePage::create([
                'slug' => 'home',
                'title' => 'Home',
            ]);
        }

        return view('admin.website-cms.cta', ['page' => $page]);
    }

    public function showFooter(?WebsitePage $page = null): View
    {
        $page = $page ?? WebsitePage::where('slug', 'home')->first();
        if (!$page) {
            $page = WebsitePage::create([
                'slug' => 'home',
                'title' => 'Home',
            ]);
        }

        return view('admin.website-cms.footer', ['page' => $page]);
    }

    public function update(Request $request, WebsitePage $page): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'site_tagline' => ['nullable', 'string', 'max:255'],
            'site_url' => ['nullable', 'url', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_address' => ['nullable', 'string', 'max:255'],
            'primary_color' => ['nullable', 'string', 'max:20'],
            'secondary_color' => ['nullable', 'string', 'max:20'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string', 'max:500'],
            'seo_keywords' => ['nullable', 'string', 'max:500'],
            'social_links' => ['nullable', 'array'],
            'social_links.*.key' => ['nullable', 'string', 'max:50'],
            'social_links.*.url' => ['nullable', 'url', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'favicon' => ['nullable', 'image', 'max:1024'],
            'hero_image' => ['nullable', 'image', 'max:4096'],
            'about_image' => ['nullable', 'image', 'max:4096'],
            'page_title' => ['required', 'string', 'max:255'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'hero_title' => ['nullable', 'string', 'max:255'],
            'hero_title_highlight' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:255'],
            'header_button_label' => ['nullable', 'string', 'max:255'],
            'header_button_url' => ['nullable', 'string', 'max:255'],
            'hero_primary_label' => ['nullable', 'string', 'max:255'],
            'hero_primary_url' => ['nullable', 'string', 'max:255'],
            'hero_secondary_label' => ['nullable', 'string', 'max:255'],
            'hero_secondary_url' => ['nullable', 'string', 'max:255'],
            'categories_title' => ['nullable', 'string', 'max:255'],
            'categories_highlight' => ['nullable', 'string', 'max:255'],
            'categories_suffix' => ['nullable', 'string', 'max:255'],
            'categories_subtitle' => ['nullable', 'string', 'max:500'],
            'categories_items' => ['nullable', 'array'],
            'categories_items.*.title' => ['nullable', 'string', 'max:255'],
            'categories_items.*.description' => ['nullable', 'string', 'max:500'],
            'categories_items.*.icon' => ['nullable', 'string', 'max:255'],
            'categories_items.*.icon_bg' => ['nullable', 'string', 'max:255'],
            'categories_items.*.icon_bg_color' => ['nullable', 'string', 'max:7'],
            'categories_items.*.icon_color' => ['nullable', 'string', 'max:255'],
            'categories_items.*.icon_color_value' => ['nullable', 'string', 'max:7'],
            'vcard_heading' => ['nullable', 'string', 'max:255'],
            'vcard_highlight' => ['nullable', 'string', 'max:255'],
            'vcard_suffix' => ['nullable', 'string', 'max:255'],
            'vcard_subheading' => ['nullable', 'string', 'max:500'],
            'vcard_initials' => ['nullable', 'string', 'max:5'],
            'vcard_name' => ['nullable', 'string', 'max:255'],
            'vcard_role' => ['nullable', 'string', 'max:255'],
            'vcard_company' => ['nullable', 'string', 'max:255'],
            'vcard_location' => ['nullable', 'string', 'max:255'],
            'vcard_email' => ['nullable', 'string', 'max:255'],
            'vcard_phone' => ['nullable', 'string', 'max:255'],
            'vcard_linkedin_label' => ['nullable', 'string', 'max:255'],
            'vcard_linkedin_url' => ['nullable', 'string', 'max:255'],
            'vcard_dribbble_label' => ['nullable', 'string', 'max:255'],
            'vcard_dribbble_url' => ['nullable', 'string', 'max:255'],
            'how_title' => ['nullable', 'string', 'max:255'],
            'how_highlight' => ['nullable', 'string', 'max:255'],
            'how_suffix' => ['nullable', 'string', 'max:255'],
            'how_subtitle' => ['nullable', 'string', 'max:500'],
            'how_steps' => ['nullable', 'array'],
            'how_steps.*.number' => ['nullable', 'string', 'max:10'],
            'how_steps.*.title' => ['nullable', 'string', 'max:255'],
            'how_steps.*.description' => ['nullable', 'string', 'max:500'],
            'how_steps.*.badge_bg' => ['nullable', 'string', 'max:255'],
            'how_steps.*.badge_text' => ['nullable', 'string', 'max:255'],
            'cta_title' => ['nullable', 'string', 'max:255'],
            'cta_subtitle' => ['nullable', 'string', 'max:500'],
            'cta_primary_label' => ['nullable', 'string', 'max:255'],
            'cta_primary_url' => ['nullable', 'string', 'max:255'],
            'cta_secondary_label' => ['nullable', 'string', 'max:255'],
            'cta_secondary_url' => ['nullable', 'string', 'max:255'],
            'about_title' => ['nullable', 'string', 'max:255'],
            'about_body' => ['nullable', 'string'],
            'footer_about' => ['nullable', 'string'],
            'footer_product_links' => ['nullable', 'array'],
            'footer_product_links.*.label' => ['nullable', 'string', 'max:255'],
            'footer_product_links.*.url' => ['nullable', 'string', 'max:255'],
            'footer_resource_links' => ['nullable', 'array'],
            'footer_resource_links.*.label' => ['nullable', 'string', 'max:255'],
            'footer_resource_links.*.url' => ['nullable', 'string', 'max:255'],
            'footer_text' => ['nullable', 'string', 'max:255'],
            'services' => ['nullable', 'array'],
            'services.*.title' => ['nullable', 'string', 'max:255'],
            'services.*.description' => ['nullable', 'string', 'max:500'],
            'testimonials' => ['nullable', 'array'],
            'testimonials.*.name' => ['nullable', 'string', 'max:255'],
            'testimonials.*.role' => ['nullable', 'string', 'max:255'],
            'testimonials.*.quote' => ['nullable', 'string', 'max:500'],
            'faqs' => ['nullable', 'array'],
            'faqs.*.question' => ['nullable', 'string', 'max:255'],
            'faqs.*.answer' => ['nullable', 'string', 'max:500'],
        ]);

        $files = [
            'logo' => 'logo_path',
            'favicon' => 'favicon_path',
            'hero_image' => 'hero_image_path',
            'about_image' => 'about_image_path',
        ];

        foreach ($files as $input => $key) {
            if ($request->hasFile($input)) {
                $path = $request->file($input)->store('website-cms', 'public');
                $validated[$key] = $path;
            }
        }

        // Process social links
        if (!empty($validated['social_links'])) {
            foreach ($validated['social_links'] as $social) {
                if (!empty($social['key']) && !empty($social['url'])) {
                    WebsiteSetting::updateOrCreate(
                        ['key' => 'social_' . $social['key']],
                        ['value' => $social['url']]
                    );
                }
            }
        }

        foreach ($this->defaultSettings() as $key => $default) {
            $value = $validated[$key] ?? WebsiteSetting::where('key', $key)->value('value') ?? $default;
            WebsiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $page->update([
            'title' => $validated['page_title'],
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'hero_title' => $validated['hero_title'] ?? null,
            'hero_subtitle' => $validated['hero_subtitle'] ?? null,
            'hero_image_path' => $validated['hero_image_path'] ?? $page->hero_image_path,
            'hero_title_highlight' => $validated['hero_title_highlight'] ?? null,
            'header_cta' => [
                'label' => $validated['header_button_label'] ?? null,
                'url' => $validated['header_button_url'] ?? null,
            ],
            'hero_buttons' => [
                [
                    'label' => $validated['hero_primary_label'] ?? null,
                    'url' => $validated['hero_primary_url'] ?? null,
                ],
                [
                    'label' => $validated['hero_secondary_label'] ?? null,
                    'url' => $validated['hero_secondary_url'] ?? null,
                ],
            ],
            'categories' => [
                'title' => $validated['categories_title'] ?? null,
                'highlight' => $validated['categories_highlight'] ?? null,
                'suffix' => $validated['categories_suffix'] ?? null,
                'subtitle' => $validated['categories_subtitle'] ?? null,
                'items' => $this->processCategories($validated['categories_items'] ?? []),
            ],
            'vcard_preview' => [
                'heading' => $validated['vcard_heading'] ?? null,
                'highlight' => $validated['vcard_highlight'] ?? null,
                'suffix' => $validated['vcard_suffix'] ?? null,
                'subheading' => $validated['vcard_subheading'] ?? null,
                'initials' => $validated['vcard_initials'] ?? null,
                'name' => $validated['vcard_name'] ?? null,
                'role' => $validated['vcard_role'] ?? null,
                'company' => $validated['vcard_company'] ?? null,
                'location' => $validated['vcard_location'] ?? null,
                'email' => $validated['vcard_email'] ?? null,
                'phone' => $validated['vcard_phone'] ?? null,
                'linkedin_label' => $validated['vcard_linkedin_label'] ?? null,
                'linkedin_url' => $validated['vcard_linkedin_url'] ?? null,
                'dribbble_label' => $validated['vcard_dribbble_label'] ?? null,
                'dribbble_url' => $validated['vcard_dribbble_url'] ?? null,
            ],
            'how_it_works' => [
                'title' => $validated['how_title'] ?? null,
                'highlight' => $validated['how_highlight'] ?? null,
                'suffix' => $validated['how_suffix'] ?? null,
                'subtitle' => $validated['how_subtitle'] ?? null,
                'steps' => $validated['how_steps'] ?? [],
            ],
            'cta_section' => [
                'title' => $validated['cta_title'] ?? null,
                'subtitle' => $validated['cta_subtitle'] ?? null,
                'primary_label' => $validated['cta_primary_label'] ?? null,
                'primary_url' => $validated['cta_primary_url'] ?? null,
                'secondary_label' => $validated['cta_secondary_label'] ?? null,
                'secondary_url' => $validated['cta_secondary_url'] ?? null,
            ],
            'about_title' => $validated['about_title'] ?? null,
            'about_body' => $validated['about_body'] ?? null,
            'about_image_path' => $validated['about_image_path'] ?? $page->about_image_path,
            'services' => $validated['services'] ?? [],
            'testimonials' => $validated['testimonials'] ?? [],
            'faqs' => $validated['faqs'] ?? [],
            'footer_text' => $validated['footer_text'] ?? null,
            'footer_about' => $validated['footer_about'] ?? null,
            'footer_links' => [
                'product' => $validated['footer_product_links'] ?? [],
                'resources' => $validated['footer_resource_links'] ?? [],
            ],
        ]);

        return back()->with('status', 'website-cms-updated');
    }

    private function loadSettings(): array
    {
        $defaults = $this->defaultSettings();
        $stored = WebsiteSetting::whereIn('key', array_keys($defaults))
            ->pluck('value', 'key')
            ->toArray();
        return array_merge($defaults, $stored);
    }

    private function processCategories(array $items): array
    {
        return array_map(function ($item) {
            return [
                'title' => $item['title'] ?? null,
                'description' => $item['description'] ?? null,
                'icon' => $item['icon'] ?? 'fas fa-building',
                'icon_bg' => !empty($item['icon_bg_color']) ? $item['icon_bg_color'] : ($item['icon_bg'] ?? 'bg-blue-100'),
                'icon_color' => !empty($item['icon_color_value']) ? $item['icon_color_value'] : ($item['icon_color'] ?? 'text-blue-600'),
            ];
        }, $items);
    }

    private function defaultSettings(): array
    {
        return [
            'site_name' => 'ManageHub',
            'site_tagline' => 'Manage your business with confidence',
            'site_url' => null,
            'contact_email' => null,
            'contact_phone' => null,
            'contact_address' => null,
            'primary_color' => '#537AEF',
            'secondary_color' => '#1a4de7',
            'seo_title' => null,
            'seo_description' => null,
            'seo_keywords' => null,
            'social_facebook' => null,
            'social_instagram' => null,
            'social_twitter' => null,
            'social_linkedin' => null,
            'logo_path' => null,
            'favicon_path' => null,
            'hero_image_path' => null,
        ];
    }
}
