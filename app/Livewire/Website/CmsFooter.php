<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\WebsitePage;
use App\Livewire\Concerns\HandlesToastValidation;

class CmsFooter extends Component
{
    use HandlesToastValidation;

    public ?WebsitePage $page = null;
    public $footer_about = '';
    public $product_links = [];
    public $resources_links = [];

    public function mount(WebsitePage $page)
    {
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

        $this->page->update(['data' => $data]);

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
