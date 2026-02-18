<?php

namespace App\Livewire\Website;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\WebsitePage;
use App\Livewire\Concerns\HandlesToastValidation;

class CmsVcard extends Component
{
    use HandlesToastValidation, WithFileUploads;

    public ?WebsitePage $page = null;
    public $vcards = [];
    public $previewFiles = [];

    public function mount(WebsitePage $page)
    {
        $this->page = $page;
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $vcardsData = $this->page->data['vcard_previews'] ?? [];
        $this->vcards = !empty($vcardsData) ? $vcardsData : [];
    }

    public function addVcard()
    {
        $this->vcards[] = [
            'title' => '',
            'category' => '',
            'preview_file' => '',
        ];
    }

    public function removeVcard($index)
    {
        unset($this->vcards[$index]);
        $this->vcards = array_values($this->vcards);
    }

    public function updatedPreviewFiles($value, $index)
    {
        if ($value) {
            $path = $value->store('vcard-previews', 'public');
            $this->vcards[$index]['preview_file'] = '/storage/' . $path;
        }
    }

    public function save()
    {
        $validated = [];
        foreach ($this->vcards as $index => $vcard) {
            $validated[] = $this->validateWithToast([
                'vcards.' . $index . '.title' => ['required', 'string', 'max:100'],
                'vcards.' . $index . '.category' => ['required', 'string', 'max:100'],
                'vcards.' . $index . '.preview_file' => ['nullable', 'string'],
            ]);
        }

        // Flatten and merge all validations
        $allValidated = [];
        foreach ($this->vcards as $index => $vcard) {
            $allValidated[$index] = [
                'title' => $vcard['title'],
                'category' => $vcard['category'],
                'preview_file' => $vcard['preview_file'] ?? '',
            ];
        }

        $data = $this->page->data ?? [];
        $data['vcard_previews'] = $allValidated;

        $this->page->update(['data' => $data]);

        $this->dispatch('notify',
            type: 'success',
            message: 'vCard previews saved successfully!'
        );
    }

    public function render()
    {
        return view('livewire.website.cms-vcard');
    }
}
