<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\WebsitePage;
use App\Livewire\Concerns\HandlesToastValidation;

class CmsCta extends Component
{
    use HandlesToastValidation;

    public ?WebsitePage $page = null;
    public $title = '';
    public $subtitle = '';
    public $primary_label = '';
    public $primary_url = '';
    public $secondary_label = '';
    public $secondary_url = '';

    public function mount(WebsitePage $page)
    {
        $this->page = $page;
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $cta = $this->page->data['cta_section'] ?? [];
        $this->title = $cta['title'] ?? '';
        $this->subtitle = $cta['subtitle'] ?? '';
        $this->primary_label = $cta['primary_label'] ?? '';
        $this->primary_url = $cta['primary_url'] ?? '';
        $this->secondary_label = $cta['secondary_label'] ?? '';
        $this->secondary_url = $cta['secondary_url'] ?? '';
    }

    public function save()
    {
        $validated = $this->validateWithToast([
            'title' => ['required', 'string'],
            'subtitle' => ['required', 'string'],
            'primary_label' => ['required', 'string'],
            'primary_url' => ['required', 'url'],
            'secondary_label' => ['required', 'string'],
            'secondary_url' => ['required', 'url'],
        ]);

        $data = $this->page->data ?? [];
        $data['cta_section'] = $validated;

        $this->page->update(['data' => $data]);

        $this->dispatch('notify',
            type: 'success',
            message: 'CTA section saved successfully!'
        );
    }

    public function render()
    {
        return view('livewire.website.cms-cta');
    }
}
