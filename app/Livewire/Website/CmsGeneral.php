<?php

namespace App\Livewire\Website;

use App\Models\WebsitePage;
use App\Livewire\Concerns\HandlesToastValidation;
use App\Repositories\Contracts\WebsiteSettingRepository;
use Livewire\Component;
use Livewire\Attributes\Locked;

class CmsGeneral extends Component
{
    use HandlesToastValidation;

    public $page;
    #[Locked] public string $pageSlug = '';
    public $settings;
    
    public $site_url;
    public $contact_email;
    public $contact_phone;
    public $contact_address;

    public function mount(WebsitePage $page)
    {
        $this->pageSlug = $page->slug;
        $this->page = $page;
        $this->settings = $this->loadSettings();
        
        $this->site_url = $this->settings['site_url'] ?? '';
        $this->contact_email = $this->settings['contact_email'] ?? '';
        $this->contact_phone = $this->settings['contact_phone'] ?? '';
        $this->contact_address = $this->settings['contact_address'] ?? '';
    }

    public function save()
    {
        $this->page = WebsitePage::where('slug', $this->pageSlug)->firstOrFail();
        
        $this->validateWithToast([
            'site_url' => 'nullable|url|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'contact_address' => 'nullable|string|max:255',
        ]);

        // Save settings
        app(WebsiteSettingRepository::class)->setMany([
            'site_url' => $this->site_url,
            'contact_email' => $this->contact_email,
            'contact_phone' => $this->contact_phone,
            'contact_address' => $this->contact_address,
        ]);

        $this->dispatch('notify',
            type: 'success',
            message: 'General information updated successfully!'
        );
    }

    private function loadSettings(): array
    {
        return app(WebsiteSettingRepository::class)->getMany([
            'site_url', 'contact_email', 'contact_phone', 'contact_address',
        ]);
    }

    public function render()
    {
        return view('livewire.website.cms-general');
    }
}
