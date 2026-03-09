<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\WebsitePage;
use App\Livewire\Concerns\HandlesToastValidation;
use App\Repositories\Contracts\WebsitePageRepository;
use Livewire\Attributes\Locked;

class CmsScripts extends Component
{
    use HandlesToastValidation;

    public ?WebsitePage $page = null;
    #[Locked] public string $pageSlug = '';
    public $head_script = '';
    public $footer_script = '';

    public function mount(WebsitePage $page)
    {
        $this->pageSlug = $page->slug;
        $this->page = $page;
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $scripts = $this->page->data['scripts'] ?? [];
        $this->head_script = $scripts['head_script'] ?? '';
        $this->footer_script = $scripts['footer_script'] ?? '';
    }

    public function save()
    {
        $this->page = WebsitePage::where('slug', $this->pageSlug)->firstOrFail();

        $validated = $this->validateWithToast([
            'head_script'   => ['nullable', 'string'],
            'footer_script' => ['nullable', 'string'],
        ]);

        $data = $this->page->data ?? [];
        $data['scripts'] = [
            'head_script'   => $validated['head_script'] ?? '',
            'footer_script' => $validated['footer_script'] ?? '',
        ];

        app(WebsitePageRepository::class)->updateData($this->page, $data);
        $this->page->data = $data;

        $this->dispatch('notify',
            type: 'success',
            message: 'Scripts saved successfully!'
        );
    }

    public function render()
    {
        return view('livewire.website.cms-scripts');
    }
}
