<?php

namespace App\Livewire\Website;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\WebsitePage;
use App\Livewire\Concerns\HandlesToastValidation;
use App\Repositories\Contracts\WebsitePageRepository;
use Livewire\Attributes\Locked;

class CmsVcard extends Component
{
    use HandlesToastValidation, WithFileUploads;

    public ?WebsitePage $page = null;
    #[Locked] public string $pageSlug = '';
    public $vcards = [];
    public $showModal = false;
    public $showPreviewModal = false;
    public $previewFile = '';
    public $editingIndex = null;
    public $sectionTitle = '';
    public $sectionSubtitle = '';
    
    // Modal form fields
    public $modalTitle = '';
    public $modalCategory = '';
    public $modalPreviewFile = null;
    public $existingPreviewFile = '';

    public function mount(WebsitePage $page)
    {
        $this->pageSlug = $page->slug;
        $this->page = $page;
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $vcardsData = $this->page->data['vcard_previews'] ?? [];
        $this->vcards = !empty($vcardsData) ? $vcardsData : [];

        $section = $this->page->data['vcard_previews_section'] ?? [];
        $this->sectionTitle = $section['title'] ?? 'vCard Previews';
        $this->sectionSubtitle = $section['subtitle'] ?? 'Explore multiple vCard styles from the CMS. Each preview opens the exact HTML file you uploaded.';
    }

    public function updatedModalPreviewFile()
    {
        // Page reload on file change to prevent model serialization issues
        if ($this->pageSlug) {
            $this->page = WebsitePage::where('slug', $this->pageSlug)->firstOrFail();
        }
    }

    public function openModal()
    {
        $this->resetModal();
        $this->editingIndex = null;
        $this->showModal = true;
    }

    public function editVcard($index)
    {
        $this->editingIndex = $index;
        if (isset($this->vcards[$index])) {
            $vcard = $this->vcards[$index];
            $this->modalTitle = $vcard['title'] ?? '';
            $this->modalCategory = $vcard['category'] ?? '';
            $this->existingPreviewFile = $vcard['preview_file'] ?? '';
            $this->modalPreviewFile = null;
            $this->showModal = true;
        }
    }

    public function closeModal()
    {
        $this->resetModal();
    }

    public function resetModal()
    {
        $this->modalTitle = '';
        $this->modalCategory = '';
        $this->modalPreviewFile = null;
        $this->existingPreviewFile = '';
        $this->editingIndex = null;
        $this->showModal = false;
    }

    public function saveVcard()
    {
        $this->validateWithToast([
            'modalTitle' => ['required', 'string', 'max:100'],
            'modalCategory' => ['required', 'string', 'max:100'],
        ]);

        $previewFilePath = $this->existingPreviewFile;

        if ($this->modalPreviewFile) {
            $previewFilePath = '/storage/' . $this->modalPreviewFile->store('vcard-previews', 'public');
        }

        $vcard = [
            'title' => $this->modalTitle,
            'category' => $this->modalCategory,
            'preview_file' => $previewFilePath,
        ];

        if ($this->editingIndex !== null) {
            $this->vcards[$this->editingIndex] = $vcard;
            $message = 'vCard preview updated successfully!';
        } else {
            $this->vcards[] = $vcard;
            $message = 'vCard preview added successfully!';
        }

        $this->saveToDatabase();
        $this->dispatch('notify', type: 'success', message: $message);
        $this->resetModal();
    }

    public function saveSection()
    {
        $this->page = WebsitePage::where('slug', $this->pageSlug)->firstOrFail();
        
        $this->validateWithToast([
            'sectionTitle' => ['required', 'string', 'max:100'],
            'sectionSubtitle' => ['required', 'string', 'max:255'],
        ]);

        $this->saveToDatabase();
        $this->dispatch('notify', type: 'success', message: 'vCard preview section updated successfully!');
    }

    public function deleteVcard($index)
    {
        $this->dispatch(
            'confirm-delete',
            id: $this->getId(),
            index: $index,
            method: 'deleteVcardConfirmed',
            message: 'Delete this vCard preview?'
        );
    }

    public function deleteVcardConfirmed($index)
    {
        if (!isset($this->vcards[$index])) {
            return;
        }

        unset($this->vcards[$index]);
        $this->vcards = array_values($this->vcards);
        $this->saveToDatabase();
        $this->dispatch('notify', type: 'success', message: 'vCard preview deleted successfully!');
    }

    public function openPreview($index)
    {
        if (isset($this->vcards[$index]) && !empty($this->vcards[$index]['preview_file'])) {
            $this->previewFile = $this->vcards[$index]['preview_file'];
            $this->showPreviewModal = true;
        }
    }

    public function closePreview()
    {
        $this->showPreviewModal = false;
        $this->previewFile = '';
    }

    public function saveToDatabase()
    {
        $this->page = WebsitePage::where('slug', $this->pageSlug)->firstOrFail();
        
        $data = $this->page->data ?? [];
        $data['vcard_previews'] = $this->vcards;
        $data['vcard_previews_section'] = [
            'title' => $this->sectionTitle,
            'subtitle' => $this->sectionSubtitle,
        ];
        app(WebsitePageRepository::class)->updateData($this->page, $data);
        $this->page->data = $data;
    }

    public function render()
    {
        return view('livewire.website.cms-vcard');
    }
}
