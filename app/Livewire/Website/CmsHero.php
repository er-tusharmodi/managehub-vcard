<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\WebsitePage;
use App\Livewire\Concerns\HandlesToastValidation;

class CmsHero extends Component
{
    use HandlesToastValidation;

    public ?WebsitePage $page = null;
    public $hero_title = '';
    public $hero_title_highlight = '';
    public $hero_subtitle = '';
    public $hero_image_path = '';
    public $cta_buttons = [];

    public function mount(WebsitePage $page)
    {
        $this->page = $page;
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $this->hero_title = $this->page->data['hero_title'] ?? '';
        $this->hero_title_highlight = $this->page->data['hero_title_highlight'] ?? '';
        $this->hero_subtitle = $this->page->data['hero_subtitle'] ?? '';
        $this->hero_image_path = $this->page->data['hero_image_path'] ?? '';
        $this->cta_buttons = $this->page->data['hero_buttons'] ?? [];
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
        $validated = $this->validateWithToast([
            'hero_title' => ['required', 'string'],
            'hero_title_highlight' => ['required', 'string'],
            'hero_subtitle' => ['required', 'string'],
            'hero_image_path' => ['nullable', 'string'],
            'cta_buttons' => ['required', 'array'],
            'cta_buttons.*.label' => ['required', 'string'],
            'cta_buttons.*.url' => ['required', 'url'],
        ]);

        $data = $this->page->data ?? [];
        $data['hero_title'] = $validated['hero_title'];
        $data['hero_title_highlight'] = $validated['hero_title_highlight'];
        $data['hero_subtitle'] = $validated['hero_subtitle'];
        $data['hero_image_path'] = $validated['hero_image_path'];
        $data['hero_buttons'] = $validated['cta_buttons'];

        $this->page->update(['data' => $data]);

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
