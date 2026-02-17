<?php

namespace App\Livewire\Website;

use App\Models\WebsitePage;
use App\Models\WebsiteSetting;
use Livewire\Component;

class CmsGeneral extends Component
{
    public $page;
    public $settings;
    
    public $site_name;
    public $site_tagline;
    public $site_url;
    public $contact_email;
    public $contact_phone;
    public $contact_address;
    public $page_title;
    public $meta_title;
    public $meta_description;

    public function mount(WebsitePage $page)
    {
        $this->page = $page;
        $this->settings = $this->loadSettings();
        
        $this->site_name = $this->settings['site_name'] ?? '';
        $this->site_tagline = $this->settings['site_tagline'] ?? '';
        $this->site_url = $this->settings['site_url'] ?? '';
        $this->contact_email = $this->settings['contact_email'] ?? '';
        $this->contact_phone = $this->settings['contact_phone'] ?? '';
        $this->contact_address = $this->settings['contact_address'] ?? '';
        $this->page_title = $page->title ?? '';
        $this->meta_title = $page->meta_title ?? '';
        $this->meta_description = $page->meta_description ?? '';
    }

    public function save()
    {
        $this->validate([
            'site_name' => 'required|string|max:255',
            'site_tagline' => 'nullable|string|max:255',
            'site_url' => 'nullable|url|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'contact_address' => 'nullable|string|max:255',
            'page_title' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        // Save settings
        WebsiteSetting::updateOrCreate(['key' => 'site_name'], ['value' => $this->site_name]);
        WebsiteSetting::updateOrCreate(['key' => 'site_tagline'], ['value' => $this->site_tagline]);
        WebsiteSetting::updateOrCreate(['key' => 'site_url'], ['value' => $this->site_url]);
        WebsiteSetting::updateOrCreate(['key' => 'contact_email'], ['value' => $this->contact_email]);
        WebsiteSetting::updateOrCreate(['key' => 'contact_phone'], ['value' => $this->contact_phone]);
        WebsiteSetting::updateOrCreate(['key' => 'contact_address'], ['value' => $this->contact_address]);

        // Save page
        $this->page->update([
            'title' => $this->page_title,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
        ]);

        $this->dispatch('notify', message: 'General information updated successfully!');
    }

    private function loadSettings(): array
    {
        return WebsiteSetting::whereIn('key', [
            'site_name', 'site_tagline', 'site_url', 'contact_email', 
            'contact_phone', 'contact_address'
        ])->pluck('value', 'key')->toArray();
    }

    public function render()
    {
        return view('livewire.website.cms-general');
    }
}
