<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\WebsitePage;

class CmsVcard extends Component
{
    public ?WebsitePage $page = null;
    public $name = '';
    public $role = '';
    public $company = '';
    public $location = '';
    public $email = '';
    public $phone = '';
    public $bio = '';

    public function mount(WebsitePage $page)
    {
        $this->page = $page;
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $vcard = $this->page->data['vcard_preview'] ?? [];
        $this->name = $vcard['name'] ?? '';
        $this->role = $vcard['role'] ?? '';
        $this->company = $vcard['company'] ?? '';
        $this->location = $vcard['location'] ?? '';
        $this->email = $vcard['email'] ?? '';
        $this->phone = $vcard['phone'] ?? '';
        $this->bio = $vcard['bio'] ?? '';
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:100'],
            'role' => ['required', 'string', 'max:100'],
            'company' => ['required', 'string', 'max:100'],
            'location' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:500'],
        ]);

        $data = $this->page->data ?? [];
        $data['vcard_preview'] = $validated;

        $this->page->update(['data' => $data]);

        $this->dispatch('notify',
            type: 'success',
            message: 'vCard preview saved successfully!'
        );
    }

    public function render()
    {
        return view('livewire.website.cms-vcard');
    }
}
