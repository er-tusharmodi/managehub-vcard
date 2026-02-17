<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\WebsitePage;

class CmsSocial extends Component
{
    public ?WebsitePage $page = null;
    public $links = [];

    public function mount(WebsitePage $page)
    {
        $this->page = $page;
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $this->links = $this->page->data['social_links'] ?? [];
    }

    public function addLink()
    {
        $this->links[] = ['platform' => '', 'url' => ''];
    }

    public function removeLink($index)
    {
        unset($this->links[$index]);
        $this->links = array_values($this->links);
    }

    public function save()
    {
        $validated = $this->validate([
            'links' => ['required', 'array'],
            'links.*.platform' => ['required', 'string'],
            'links.*.url' => ['required', 'url'],
        ]);

        $data = $this->page->data ?? [];
        $data['social_links'] = $validated['links'];

        $this->page->update(['data' => $data]);

        $this->dispatch('notify',
            type: 'success',
            message: 'Social links saved successfully!'
        );
    }

    public function render()
    {
        return view('livewire.website.cms-social');
    }
}
