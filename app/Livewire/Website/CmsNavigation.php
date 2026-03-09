<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\WebsitePage;
use App\Livewire\Concerns\HandlesToastValidation;
use App\Repositories\Contracts\WebsitePageRepository;
use Livewire\Attributes\Locked;

class CmsNavigation extends Component
{
    use HandlesToastValidation;

    public ?WebsitePage $page = null;
    #[Locked] public string $pageSlug = '';
    public $nav_links = [];

    public function mount(WebsitePage $page)
    {
        $this->pageSlug = $page->slug;
        $this->page = $page;
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $this->nav_links = $this->page->data['nav_links'] ?? [
            ['label' => 'Features',     'url' => '#features'],
            ['label' => 'Categories',   'url' => '#categories'],
            ['label' => 'How It Works', 'url' => '#how-it-works'],
            ['label' => 'Contact',      'url' => '#contact'],
        ];
    }

    public function addLink()
    {
        $this->nav_links[] = ['label' => '', 'url' => ''];
    }

    public function removeLink($index)
    {
        $this->dispatch(
            'confirm-delete',
            id: $this->getId(),
            index: $index,
            method: 'removeLinkConfirmed',
            message: 'Delete this nav link?'
        );
    }

    public function removeLinkConfirmed($index)
    {
        if (!isset($this->nav_links[$index])) {
            return;
        }
        unset($this->nav_links[$index]);
        $this->nav_links = array_values($this->nav_links);
    }

    public function save()
    {
        $this->page = WebsitePage::where('slug', $this->pageSlug)->firstOrFail();

        $validated = $this->validateWithToast([
            'nav_links'           => ['required', 'array', 'min:1'],
            'nav_links.*.label'   => ['required', 'string'],
            'nav_links.*.url'     => ['required', 'string'],
        ]);

        $data = $this->page->data ?? [];
        $data['nav_links'] = $validated['nav_links'];

        app(WebsitePageRepository::class)->updateData($this->page, $data);
        $this->page->data = $data;

        $this->dispatch('notify', type: 'success', message: 'Navigation links saved successfully!');
    }

    public function render()
    {
        return view('livewire.website.cms-navigation');
    }
}
