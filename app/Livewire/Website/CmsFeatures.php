<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\WebsitePage;
use App\Livewire\Concerns\HandlesToastValidation;
use App\Repositories\Contracts\WebsitePageRepository;
use Livewire\Attributes\Locked;

class CmsFeatures extends Component
{
    use HandlesToastValidation;

    public ?WebsitePage $page = null;
    #[Locked] public string $pageSlug = '';
    public $badge = '';
    public $title = '';
    public $title_highlight = '';
    public $subtitle = '';
    public $items = [];

    public function mount(WebsitePage $page)
    {
        $this->pageSlug = $page->slug;
        $this->page = $page;
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $features = $this->page->data['features'] ?? [];
        $this->badge = $features['badge'] ?? '';
        $this->title = $features['title'] ?? '';
        $this->title_highlight = $features['title_highlight'] ?? '';
        $this->subtitle = $features['subtitle'] ?? '';
        $this->items = $features['items'] ?? [];
    }

    public function addItem()
    {
        $this->items[] = [
            'icon' => 'fas fa-star',
            'title' => '',
            'desc' => '',
        ];
    }

    public function removeItem($index)
    {
        $this->dispatch(
            'confirm-delete',
            id: $this->getId(),
            index: $index,
            method: 'removeItemConfirmed',
            message: 'Delete this feature?'
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
            'badge'           => ['required', 'string'],
            'title'           => ['required', 'string'],
            'title_highlight' => ['required', 'string'],
            'subtitle'        => ['required', 'string'],
            'items'           => ['required', 'array', 'min:1'],
            'items.*.icon'    => ['required', 'string'],
            'items.*.title'   => ['required', 'string'],
            'items.*.desc'    => ['required', 'string'],
        ]);

        $data = $this->page->data ?? [];
        $data['features'] = $validated;

        app(WebsitePageRepository::class)->updateData($this->page, $data);
        $this->page->data = $data;

        $this->dispatch('notify',
            type: 'success',
            message: 'Features section saved successfully!'
        );
    }

    public function render()
    {
        return view('livewire.website.cms-features');
    }
}
