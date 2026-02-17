<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\WebsitePage;

class CmsBranding extends Component
{
    public ?WebsitePage $page = null;
    public $logo_url = '';
    public $favicon_url = '';
    public $primary_color = '#000000';
    public $secondary_color = '#666666';

    public function mount(WebsitePage $page)
    {
        $this->page = $page;
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $branding = $this->page->data['branding'] ?? [];
        $this->logo_url = $branding['logo_url'] ?? '';
        $this->favicon_url = $branding['favicon_url'] ?? '';
        $this->primary_color = $branding['primary_color'] ?? '#000000';
        $this->secondary_color = $branding['secondary_color'] ?? '#666666';
    }

    public function save()
    {
        $validated = $this->validate([
            'logo_url' => ['nullable', 'string', 'url'],
            'favicon_url' => ['nullable', 'string', 'url'],
            'primary_color' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'secondary_color' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        ]);

        $data = $this->page->data ?? [];
        $data['branding'] = $validated;

        $this->page->update(['data' => $data]);

        $this->dispatch('notify',
            type: 'success',
            message: 'Branding settings saved successfully!'
        );
    }

    public function render()
    {
        return view('livewire.website.cms-branding');
    }
}
