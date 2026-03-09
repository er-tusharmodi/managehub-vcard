<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\WebsitePage;
use App\Livewire\Concerns\HandlesToastValidation;
use App\Repositories\Contracts\WebsitePageRepository;
use Livewire\Attributes\Locked;

class CmsStats extends Component
{
    use HandlesToastValidation;

    public ?WebsitePage $page = null;
    #[Locked] public string $pageSlug = '';
    public $items = [];

    public function mount(WebsitePage $page)
    {
        $this->pageSlug = $page->slug;
        $this->page = $page;
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $stats = $this->page->data['stats'] ?? [];
        $this->items = $stats['items'] ?? [
            ['number' => '9',   'suffix' => '+',      'label' => 'Templates'],
            ['number' => '100', 'suffix' => '%',       'label' => 'Customizable'],
            ['number' => '1',   'suffix' => '-Click',  'label' => 'Contact Save'],
            ['number' => '24',  'suffix' => '/7',      'label' => 'Online Presence'],
        ];
    }

    public function addItem()
    {
        $this->items[] = ['number' => '', 'suffix' => '', 'label' => ''];
    }

    public function removeItem($index)
    {
        $this->dispatch(
            'confirm-delete',
            id: $this->getId(),
            index: $index,
            method: 'removeItemConfirmed',
            message: 'Delete this stat?'
        );
    }

    public function removeItemConfirmed($index)
    {
        if (!isset($this->items[$index])) {
            return;
        }
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function save()
    {
        $this->page = WebsitePage::where('slug', $this->pageSlug)->firstOrFail();

        $validated = $this->validateWithToast([
            'items'             => ['required', 'array', 'min:1'],
            'items.*.number'    => ['required', 'string'],
            'items.*.suffix'    => ['nullable', 'string'],
            'items.*.label'     => ['required', 'string'],
        ]);

        $data = $this->page->data ?? [];
        $data['stats'] = ['items' => $validated['items']];

        app(WebsitePageRepository::class)->updateData($this->page, $data);
        $this->page->data = $data;

        $this->dispatch('notify', type: 'success', message: 'Stats bar saved successfully!');
    }

    public function render()
    {
        return view('livewire.website.cms-stats');
    }
}
