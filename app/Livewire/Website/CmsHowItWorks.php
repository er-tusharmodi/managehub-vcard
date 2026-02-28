<?php

namespace App\Livewire\Website;

use Livewire\Component;
use App\Models\WebsitePage;
use App\Livewire\Concerns\HandlesToastValidation;
use App\Repositories\Contracts\WebsitePageRepository;
use Livewire\Attributes\Locked;

class CmsHowItWorks extends Component
{
    use HandlesToastValidation;

    public ?WebsitePage $page = null;
    #[Locked] public string $pageSlug = '';
    public $steps = [];
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
        $howitworks = $this->page->data['how_it_works'] ?? [];
        $this->steps = $howitworks['steps'] ?? [];
        $this->title = $howitworks['title'] ?? '';
        $this->subtitle = $howitworks['subtitle'] ?? '';
        $this->highlight = $howitworks['highlight'] ?? '';
    }

    public function addStep()
    {
        $this->steps[] = [
            'title' => '',
            'number' => count($this->steps) + 1,
            'badge_bg' => 'bg-blue-100',
            'badge_text' => 'text-blue-700',
            'description' => ''
        ];
    }

    public function removeStep($index)
    {
        $this->dispatch(
            'confirm-delete',
            id: $this->getId(),
            index: $index,
            method: 'removeStepConfirmed',
            message: 'Delete this step?'
        );
    }

    public function removeStepConfirmed($index)
    {
        if (!isset($this->steps[$index])) {
            return;
        }

        unset($this->steps[$index]);
        $this->steps = array_values(array_map(function ($step, $index) {
            $step['number'] = $index + 1;
            return $step;
        }, $this->steps, array_keys($this->steps)));
    }

    public function save()
    {
        $this->page = WebsitePage::where('slug', $this->pageSlug)->firstOrFail();
        
        $validated = $this->validateWithToast([
            'title' => ['required', 'string'],
            'subtitle' => ['required', 'string'],
            'highlight' => ['required', 'string'],
            'steps' => ['required', 'array', 'min:1'],
            'steps.*.title' => ['required', 'string'],
            'steps.*.number' => ['required', 'integer'],
            'steps.*.badge_bg' => ['required', 'string'],
            'steps.*.badge_text' => ['required', 'string'],
            'steps.*.description' => ['required', 'string'],
        ]);

        $data = $this->page->data ?? [];
        $data['how_it_works'] = [
            'steps' => $validated['steps'],
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'highlight' => $validated['highlight'],
            'suffix' => '',
        ];

        app(WebsitePageRepository::class)->updateData($this->page, $data);
        $this->page->data = $data;

        $this->dispatch('notify',
            type: 'success',
            message: 'How It Works section saved successfully!'
        );
    }

    public function render()
    {
        return view('livewire.website.cms-how-it-works');
    }
}
