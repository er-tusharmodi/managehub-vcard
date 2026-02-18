<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\WebsitePage;
use App\Livewire\Concerns\HandlesToastValidation;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class CmsBranding extends Component
{
    use HandlesToastValidation;
    use WithFileUploads;

    public ?WebsitePage $page = null;
    public $logo_url = '';
    public $favicon_url = '';
    public $footer_logo_url = '';
    public $primary_color = '#000000';
    public $secondary_color = '#666666';
    public $logo_file;
    public $favicon_file;
    public $footer_logo_file;

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
        $this->footer_logo_url = $branding['footer_logo_url'] ?? '';
        $this->primary_color = $branding['primary_color'] ?? '#000000';
        $this->secondary_color = $branding['secondary_color'] ?? '#666666';
    }

    public function save()
    {
        $validated = $this->validateWithToast([
            'logo_file' => ['nullable', 'mimes:png,jpg,jpeg,svg', 'max:2048'],
            'favicon_file' => ['nullable', 'mimes:png,jpg,jpeg,svg,ico', 'max:1024'],
            'footer_logo_file' => ['nullable', 'mimes:png,jpg,jpeg,svg', 'max:2048'],
            'primary_color' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'secondary_color' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
        ]);

        if ($this->logo_file) {
            $logoPath = $this->logo_file->store('branding', 'public');
            $this->logo_url = Storage::disk('public')->url($logoPath);
        }

        if ($this->favicon_file) {
            $faviconPath = $this->favicon_file->store('branding', 'public');
            $this->favicon_url = Storage::disk('public')->url($faviconPath);
        }

        if ($this->footer_logo_file) {
            $footerLogoPath = $this->footer_logo_file->store('branding', 'public');
            $this->footer_logo_url = Storage::disk('public')->url($footerLogoPath);
        }

        $data = $this->page->data ?? [];
        $data['branding'] = [
            'logo_url' => $this->logo_url,
            'favicon_url' => $this->favicon_url,
            'footer_logo_url' => $this->footer_logo_url,
            'primary_color' => $validated['primary_color'],
            'secondary_color' => $validated['secondary_color'],
        ];

        $this->page->update(['data' => $data]);

        $this->logo_file = null;
        $this->favicon_file = null;
        $this->footer_logo_file = null;

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
