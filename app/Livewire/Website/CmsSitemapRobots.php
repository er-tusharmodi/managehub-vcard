<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\WebsitePage;
use App\Models\Vcard;
use App\Livewire\Concerns\HandlesToastValidation;
use App\Repositories\Contracts\WebsitePageRepository;
use App\Repositories\Contracts\WebsiteSettingRepository;
use Livewire\Attributes\Locked;

class CmsSitemapRobots extends Component
{
    use HandlesToastValidation;

    public ?WebsitePage $page = null;
    #[Locked] public string $pageSlug = '';
    public $robots_txt = '';
    public $sitemap_xml = '';

    public function mount(WebsitePage $page)
    {
        $this->pageSlug = $page->slug;
        $this->page = $page;
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $this->robots_txt = $this->page->data['robots_txt'] ?? '';
        $this->sitemap_xml = $this->page->data['sitemap_xml'] ?? '';
    }

    public function saveRobots()
    {
        $this->page = WebsitePage::where('slug', $this->pageSlug)->firstOrFail();

        $validated = $this->validateWithToast([
            'robots_txt' => ['nullable', 'string'],
        ]);

        $data = $this->page->data ?? [];
        $data['robots_txt'] = $validated['robots_txt'] ?? '';

        app(WebsitePageRepository::class)->updateData($this->page, $data);
        $this->page->data = $data;

        file_put_contents(public_path('robots.txt'), $data['robots_txt']);

        $this->dispatch('notify',
            type: 'success',
            message: 'robots.txt saved and written to public/robots.txt!'
        );
    }

    public function saveSitemap()
    {
        $this->page = WebsitePage::where('slug', $this->pageSlug)->firstOrFail();

        $validated = $this->validateWithToast([
            'sitemap_xml' => ['nullable', 'string'],
        ]);

        $data = $this->page->data ?? [];
        $data['sitemap_xml'] = $validated['sitemap_xml'] ?? '';

        app(WebsitePageRepository::class)->updateData($this->page, $data);
        $this->page->data = $data;

        file_put_contents(public_path('sitemap.xml'), $data['sitemap_xml']);

        $this->dispatch('notify',
            type: 'success',
            message: 'sitemap.xml saved and written to public/sitemap.xml!'
        );
    }

    public function autoGenerateRobots()
    {
        try {
            $siteUrl = rtrim(app(WebsiteSettingRepository::class)->get('site_url') ?? config('app.url'), '/');

            $this->robots_txt = implode("\n", [
                'User-agent: *',
                'Allow: /',
                'Disallow: /admin/',
                '',
                'Sitemap: ' . $siteUrl . '/sitemap.xml',
            ]);

            $this->dispatch('notify',
                type: 'success',
                message: 'robots.txt auto-generated. Click "Save robots.txt" to apply.'
            );
        } catch (\Throwable $e) {
            $this->dispatch('notify',
                type: 'error',
                message: 'Failed to auto-generate robots.txt: ' . $e->getMessage()
            );
        }
    }

    public function autoGenerateSitemap()
    {
        try {
            $siteUrl    = rtrim(app(WebsiteSettingRepository::class)->get('site_url') ?? config('app.url'), '/');
            $baseDomain = config('vcard.base_domain', 'managehub.in');
            $now        = now()->toAtomString();

            $urls = [];

            // Homepage
            $urls[] = [
                'loc'        => $siteUrl . '/',
                'lastmod'    => $now,
                'changefreq' => 'daily',
                'priority'   => '1.0',
            ];

            // All active vCards (each on its own subdomain)
            $vcards = Vcard::where('status', 'active')
                ->whereNotNull('subdomain')
                ->where('subdomain', '!=', '')
                ->get(['subdomain', 'updated_at']);

            foreach ($vcards as $vcard) {
                $urls[] = [
                    'loc'        => 'https://' . $vcard->subdomain . '.' . $baseDomain . '/',
                    'lastmod'    => optional($vcard->updated_at)->toAtomString() ?? $now,
                    'changefreq' => 'weekly',
                    'priority'   => '0.8',
                ];
            }

            // Build XML
            $xml  = '<' . '?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
            foreach ($urls as $url) {
                $xml .= "  <url>\n";
                $xml .= '    <loc>' . htmlspecialchars($url['loc'], ENT_XML1) . '</loc>' . "\n";
                $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . "\n";
                $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . "\n";
                $xml .= '    <priority>' . $url['priority'] . '</priority>' . "\n";
                $xml .= "  </url>\n";
            }
            $xml .= '</urlset>';

            $this->sitemap_xml = $xml;

            $this->dispatch('notify',
                type: 'success',
                message: 'Sitemap auto-generated with ' . count($urls) . ' URL(s). Click "Save sitemap.xml" to apply.'
            );
        } catch (\Throwable $e) {
            $this->dispatch('notify',
                type: 'error',
                message: 'Failed to auto-generate sitemap: ' . $e->getMessage()
            );
        }
    }

    public function render()
    {
        return view('livewire.website.cms-sitemap-robots');
    }
}
