<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\WebsitePage;
use App\Livewire\Concerns\HandlesToastValidation;
use App\Repositories\Contracts\WebsitePageRepository;
use Livewire\Attributes\Locked;

class CmsFooter extends Component
{
    use HandlesToastValidation;

    public ?WebsitePage $page = null;
    #[Locked] public string $pageSlug = '';
    public $footer_about = '';
    public $product_links = [];
    public $resources_links = [];

    public function mount(WebsitePage $page)
    {
        $this->pageSlug = $page->slug;
        $this->page = $page;
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $this->footer_about = $this->page->data['footer_about'] ?? '';
        $footer_links = $this->page->data['footer_links'] ?? [];
        $this->product_links = $footer_links['product'] ?? [];
        $this->resources_links = $footer_links['resources'] ?? [];
    }

    public function addProductLink()
    {
        $this->product_links[] = ['label' => '', 'url' => ''];
    }

    public function removeProductLink($index)
    {
        $this->dispatch(
            'confirm-delete',
            id: $this->getId(),
            index: $index,
            method: 'removeProductLinkConfirmed',
            message: 'Delete this product link?'
        );
    }

    public function removeProductLinkConfirmed($index)
    {
        if (!isset($this->product_links[$index])) {
            return;
        }

        unset($this->product_links[$index]);
        $this->product_links = array_values($this->product_links);
    }

    public function addResourceLink()
    {
        $this->resources_links[] = ['label' => '', 'url' => ''];
    }

    public function removeResourceLink($index)
    {
        $this->dispatch(
            'confirm-delete',
            id: $this->getId(),
            index: $index,
            method: 'removeResourceLinkConfirmed',
            message: 'Delete this resource link?'
        );
    }

    public function removeResourceLinkConfirmed($index)
    {
        if (!isset($this->resources_links[$index])) {
            return;
        }

        unset($this->resources_links[$index]);
        $this->resources_links = array_values($this->resources_links);
    }

    public function save()
    {
        $this->page = WebsitePage::where('slug', $this->pageSlug)->firstOrFail();
        
        $validated = $this->validateWithToast([
            'footer_about' => ['required', 'string'],
            'product_links' => ['required', 'array'],
            'product_links.*.label' => ['required', 'string'],
            'product_links.*.url' => ['required', 'url'],
            'resources_links' => ['required', 'array'],
            'resources_links.*.label' => ['required', 'string'],
            'resources_links.*.url' => ['required', 'url'],
        ]);

        $data = $this->page->data ?? [];
        $data['footer_about'] = $validated['footer_about'];
        $data['footer_links'] = [
            'product' => $validated['product_links'],
            'resources' => $validated['resources_links'],
        ];

        app(WebsitePageRepository::class)->updateData($this->page, $data);
        $this->page->data = $data;

        $this->dispatch('notify',
            type: 'success',
            message: 'Footer links saved successfully!'
        );
    }

    public function render()
    {
        return view('livewire.website.cms-footer');
    }
}
