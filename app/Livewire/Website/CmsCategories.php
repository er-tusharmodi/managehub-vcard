<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\WebsitePage;
use App\Livewire\Concerns\HandlesToastValidation;
use App\Repositories\Contracts\WebsitePageRepository;
use Livewire\Attributes\Locked;

class CmsCategories extends Component
{
    use HandlesToastValidation;

    public ?WebsitePage $page = null;
    #[Locked] public string $pageSlug = '';
    public $categories = [];
    public $title = '';
    public $subtitle = '';
    public $highlight = '';

    public function mount(WebsitePage $page)
    {
        $this->pageSlug = $page->slug;
        $this->page = $page;
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $categories = $this->page->data['categories'] ?? [];
        $this->categories = $categories['items'] ?? [];
        $this->title = $categories['title'] ?? '';
        $this->subtitle = $categories['subtitle'] ?? '';
        $this->highlight = $categories['highlight'] ?? '';
    }

    public function addCategory()
    {
        $this->categories[] = [
            'icon' => 'fas fa-star',
            'title' => '',
            'icon_bg' => '#ffffff',
            'icon_color' => '#000000',
            'description' => ''
        ];
    }

    public function removeCategory($index)
    {
        $this->dispatch(
            'confirm-delete',
            id: $this->getId(),
            index: $index,
            method: 'removeCategoryConfirmed',
            message: 'Delete this category?'
        );
    }

    public function removeCategoryConfirmed($index)
    {
        if (!isset($this->categories[$index])) {
            return;
        }

        unset($this->categories[$index]);
        $this->categories = array_values($this->categories);
    }

    public function save()
    {
        $this->page = WebsitePage::where('slug', $this->pageSlug)->firstOrFail();
        
        $validated = $this->validateWithToast([
            'title' => ['required', 'string'],
            'subtitle' => ['required', 'string'],
            'highlight' => ['required', 'string'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*.icon' => ['required', 'string'],
            'categories.*.title' => ['required', 'string'],
            'categories.*.icon_bg' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'categories.*.icon_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'categories.*.description' => ['required', 'string'],
        ]);

        $data = $this->page->data ?? [];
        $data['categories'] = [
            'items' => $validated['categories'],
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'highlight' => $validated['highlight'],
        ];

        app(WebsitePageRepository::class)->updateData($this->page, $data);
        $this->page->data = $data;

        $this->dispatch('notify',
            type: 'success',
            message: 'Categories saved successfully!'
        );
    }

    public function render()
    {
        return view('livewire.website.cms-categories');
    }
}
