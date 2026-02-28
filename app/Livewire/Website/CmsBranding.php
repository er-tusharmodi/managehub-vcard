<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\WebsitePage;
use App\Livewire\Concerns\HandlesToastValidation;
use App\Repositories\Contracts\WebsitePageRepository;
use Livewire\WithFileUploads;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\Storage;

class CmsBranding extends Component
{
    use HandlesToastValidation;
    use WithFileUploads;

    #[Locked]
    public string $pageSlug = '';

    public ?WebsitePage $page = null;
    public $logo_url = '';
    public $favicon_url = '';
    public $footer_logo_url = '';
    public $primary_color = '#000000';
    public $secondary_color = '#666666';
    public $logo_file = null;
    public $favicon_file = null;
    public $footer_logo_file = null;

    public function mount(WebsitePage $page)
    {
        // Store only the slug in a locked property to avoid model serialization issues
        $this->pageSlug = $page->slug;
        $this->page = $page;
        $this->loadSettings();
    }

    public function loadSettings()
    {
        // Reload page fresh from DB to ensure we have latest data
        if (!$this->page || $this->page->slug !== $this->pageSlug) {
            $this->page = WebsitePage::where('slug', $this->pageSlug)->firstOrFail();
        }
        
        $branding = $this->page->data['branding'] ?? [];
        $this->logo_url = $branding['logo_url'] ?? '';
        $this->favicon_url = $branding['favicon_url'] ?? '';
        $this->footer_logo_url = $branding['footer_logo_url'] ?? '';
        $this->primary_color = $branding['primary_color'] ?? '#000000';
        $this->secondary_color = $branding['secondary_color'] ?? '#666666';
    }

    public function updatedLogoFile()
    {
        // Page reload on file change to prevent model serialization issues
        if ($this->pageSlug) {
            $this->page = WebsitePage::where('slug', $this->pageSlug)->firstOrFail();
        }
    }

    public function updatedFaviconFile()
    {
        // Page reload on file change to prevent model serialization issues
        if ($this->pageSlug) {
            $this->page = WebsitePage::where('slug', $this->pageSlug)->firstOrFail();
        }
    }

    public function updatedFooterLogoFile()
    {
        // Page reload on file change to prevent model serialization issues
        if ($this->pageSlug) {
            $this->page = WebsitePage::where('slug', $this->pageSlug)->firstOrFail();
        }
    }

    public function save()
    {
        \Log::info('CmsBranding.save() called with slug: ' . $this->pageSlug);
        
        try {
            // Reload page fresh from DB - critical for MongoDB models
            $this->page = WebsitePage::where('slug', $this->pageSlug)->firstOrFail();
            \Log::info('Page reloaded from DB: ' . $this->pageSlug);

            $validated = $this->validateWithToast([
                'logo_file' => ['nullable', 'mimes:png,jpg,jpeg,svg', 'max:2048'],
                'favicon_file' => ['nullable', 'mimes:png,jpg,jpeg,svg,ico', 'max:1024'],
                'footer_logo_file' => ['nullable', 'mimes:png,jpg,jpeg,svg', 'max:2048'],
                'primary_color' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
                'secondary_color' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            ]);

            \Log::info('CmsBranding validation passed');

            if ($this->logo_file) {
                try {
                    $logoPath = $this->logo_file->store('branding', 'public');
                    $this->logo_url = Storage::disk('public')->url($logoPath);
                    \Log::info('Logo uploaded: ' . $logoPath);
                } catch (\Exception $e) {
                    \Log::error('Logo upload failed: ' . $e->getMessage());
                    throw $e;
                }
            }

            if ($this->favicon_file) {
                try {
                    $faviconPath = $this->favicon_file->store('branding', 'public');
                    $this->favicon_url = Storage::disk('public')->url($faviconPath);
                    \Log::info('Favicon uploaded: ' . $faviconPath);
                } catch (\Exception $e) {
                    \Log::error('Favicon upload failed: ' . $e->getMessage());
                    throw $e;
                }
            }

            if ($this->footer_logo_file) {
                try {
                    $footerLogoPath = $this->footer_logo_file->store('branding', 'public');
                    $this->footer_logo_url = Storage::disk('public')->url($footerLogoPath);
                    \Log::info('Footer logo uploaded: ' . $footerLogoPath);
                } catch (\Exception $e) {
                    \Log::error('Footer logo upload failed: ' . $e->getMessage());
                    throw $e;
                }
            }

            $data = $this->page->data ?? [];
            $data['branding'] = [
                'logo_url' => $this->logo_url,
                'favicon_url' => $this->favicon_url,
                'footer_logo_url' => $this->footer_logo_url,
                'primary_color' => $validated['primary_color'],
                'secondary_color' => $validated['secondary_color'],
            ];

            try {
                app(WebsitePageRepository::class)->updateData($this->page, $data);
                $this->page->data = $data;
                \Log::info('Branding data saved to repository');
            } catch (\Exception $e) {
                \Log::error('Failed to save branding data: ' . $e->getMessage());
                throw $e;
            }

            $this->logo_file = null;
            $this->favicon_file = null;
            $this->footer_logo_file = null;

            $this->dispatch('notify',
                type: 'success',
                message: 'Branding settings saved successfully!'
            );

            \Log::info('CmsBranding.save() completed successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('CmsBranding validation failed', ['errors' => $e->errors()]);
            throw $e;
        } catch (\Exception $e) {
            \Log::error('CmsBranding.save() error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            $this->dispatch('notify',
                type: 'error',
                message: 'Error saving branding settings. Please check logs.'
            );
        }
    }

    public function render()
    {
        return view('livewire.website.cms-branding');
    }
}
