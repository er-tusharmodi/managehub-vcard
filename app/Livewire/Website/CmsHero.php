<?php

namespace App\Livewire\Website;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\WebsitePage;
use App\Livewire\Concerns\HandlesToastValidation;
use App\Repositories\Contracts\WebsitePageRepository;
use Livewire\Attributes\Locked;

class CmsHero extends Component
{
    use HandlesToastValidation, WithFileUploads;

    public ?WebsitePage $page = null;
    #[Locked] public string $pageSlug = '';
    public $hero_title = '';
    public $hero_title_highlight = '';
    public $hero_subtitle = '';
    public $hero_image_path = '';
    public $hero_image_file = null;
    public $cta_buttons = [];

    public function mount(WebsitePage $page)
    {
        $this->pageSlug = $page->slug;
        $this->page = $page;
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $this->hero_title = $this->page->data['hero_title'] ?? '';
        $this->hero_title_highlight = $this->page->data['hero_title_highlight'] ?? '';
        $this->hero_subtitle = $this->page->data['hero_subtitle'] ?? '';
        $this->hero_image_path = $this->page->data['hero_image_path'] ?? '';
        $this->hero_image_file = null;
        $this->cta_buttons = $this->page->data['hero_buttons'] ?? [];
    }

    public function updatedHeroImageFile()
    {
        // Page reload on file change to prevent model serialization issues
        if ($this->pageSlug) {
            $this->page = WebsitePage::where('slug', $this->pageSlug)->firstOrFail();
        }
    }

    public function addButton()
    {
        $this->cta_buttons[] = ['label' => '', 'url' => ''];
    }

    public function removeButton($index)
    {
        unset($this->cta_buttons[$index]);
        $this->cta_buttons = array_values($this->cta_buttons);
    }

    public function save()
    {
        $this->page = WebsitePage::where('slug', $this->pageSlug)->firstOrFail();
        
        $validated = $this->validateWithToast([
            'hero_title' => ['required', 'string'],
            'hero_title_highlight' => ['required', 'string'],
            'hero_subtitle' => ['required', 'string'],
            'hero_image_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp'],
            'cta_buttons' => ['required', 'array'],
            'cta_buttons.*.label' => ['required', 'string'],
            'cta_buttons.*.url' => ['required', 'url'],
        ]);

        if ($this->hero_image_file) {
            $storedPath = $this->hero_image_file->store('hero-images', 'public');
            $this->hero_image_path = '/storage/' . $storedPath;
        }

        $data = $this->page->data ?? [];
        $data['hero_title'] = $validated['hero_title'];
        $data['hero_title_highlight'] = $validated['hero_title_highlight'];
        $data['hero_subtitle'] = $validated['hero_subtitle'];
        $data['hero_image_path'] = $this->hero_image_path;
        $data['hero_buttons'] = $validated['cta_buttons'];

        app(WebsitePageRepository::class)->updateData($this->page, $data);
        $this->page->data = $data;

        $this->dispatch('notify',
            type: 'success',
            message: 'Hero section saved successfully!'
        );
    }

    public function render()
    {
        return view('livewire.website.cms-hero');
    }
}
