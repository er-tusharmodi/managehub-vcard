<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\WebsitePage;
use App\Livewire\Concerns\HandlesToastValidation;
use App\Repositories\Contracts\WebsitePageRepository;
use Livewire\Attributes\Locked;

class CmsSeo extends Component
{
    use HandlesToastValidation;

    public ?WebsitePage $page = null;
    #[Locked] public string $pageSlug = '';
    public $meta_title = '';
    public $meta_description = '';
    public $meta_keywords = '';
    public $canonical_url = '';

    public function mount(WebsitePage $page)
    {
        $this->pageSlug = $page->slug;
        $this->page = $page;
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $this->meta_title = $this->page->meta_title ?? '';
        $this->meta_description = $this->page->meta_description ?? '';
        $seo = $this->page->data['seo'] ?? [];
        $this->meta_keywords = $seo['meta_keywords'] ?? '';
        $this->canonical_url = $seo['canonical_url'] ?? '';
    }

    public function save()
    {
        $this->page = WebsitePage::where('slug', $this->pageSlug)->firstOrFail();
        
        $validated = $this->validateWithToast([
            'meta_title' => ['required', 'string', 'max:160'],
            'meta_description' => ['required', 'string', 'max:160'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url'],
        ]);

        app(WebsitePageRepository::class)->updateAttributes($this->page, [
            'meta_title' => $validated['meta_title'],
            'meta_description' => $validated['meta_description'],
        ]);

        $data = $this->page->data ?? [];
        $data['seo'] = [
            'meta_keywords' => $validated['meta_keywords'],
            'canonical_url' => $validated['canonical_url'],
        ];

        app(WebsitePageRepository::class)->updateData($this->page, $data);
        $this->page->meta_title = $validated['meta_title'];
        $this->page->meta_description = $validated['meta_description'];
        $this->page->data = $data;

        $this->dispatch('notify',
            type: 'success',
            message: 'SEO settings saved successfully!'
        );
    }

    public function render()
    {
        return view('livewire.website.cms-seo');
    }
}
