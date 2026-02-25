<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\VcardTemplateService;
use App\Services\TemplateBackupService;
use Illuminate\Support\Facades\File;

class TemplateVisualEditor extends Component
{
    use WithFileUploads;

    public $templateKey;
    public $templateName;
    public ?string $section = null;
    public array $sections = [];
    public array $form = [];
    public array $uploads = [];
    public array $newItem = [];
    public bool $showIndex = false;

    protected $templateService;
    protected $backupService;

    public function boot(VcardTemplateService $templateService, TemplateBackupService $backupService)
    {
        $this->templateService = $templateService;
        $this->backupService = $backupService;
    }

    public function mount($templateKey, ?string $section = null)
    {
        $this->templateKey = $templateKey;
        $this->templateName = ucwords(str_replace('-', ' ', $templateKey));
        $this->section = $section;

        try {
            $data = $this->templateService->getTemplateDefaultJson($templateKey);
            $this->sections = array_keys($data);

            if ($section === null) {
                $this->showIndex = true;
                return;
            }

            if (in_array($section, $this->sections, true)) {
                $this->section = $section;
            } else {
                $this->section = $this->sections[0] ?? 'meta';
            }

            $sectionData = $data[$this->section] ?? [];
            
            // Reindex numeric-keyed arrays to sequential format
            if (is_array($sectionData) && !empty($sectionData)) {
                $keys = array_keys($sectionData);
                if (isset($keys[0]) && is_numeric($keys[0]) && $keys === range(0, count($keys) - 1)) {
                    $sectionData = array_values($sectionData);
                }
            }
            
            $this->form = $sectionData;
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->route('admin.templates.index');
        }
    }

    public function save()
    {
        try {
            $payload = $this->applyUploads($this->form, $this->uploads);
            $data = $this->templateService->getTemplateDefaultJson($this->templateKey);
            $data[$this->section] = $payload;

            // Create backup before saving
            $this->backupService->backup($this->templateKey);
            
            $this->templateService->updateTemplateDefaultJson($this->templateKey, $data);
            $this->form = $payload;
            $this->uploads = [];

            session()->flash('success', 'Template data updated successfully.');
            \Log::info('Template saved successfully', ['template' => $this->templateKey, 'section' => $this->section]);
        } catch (\Exception $e) {
            \Log::error('Template save failed', [
                'template' => $this->templateKey,
                'section' => $this->section,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Failed to save template: ' . $e->getMessage());
        }
    }

    public function saveAndNotify()
    {
        $this->validateIfRules($this->rulesForForm());
        $this->save();
        $this->dispatch('notify', type: 'success', message: 'Changes saved successfully!');
        $this->dispatch('close-modal');
    }

    public function addRow(string $path, array $columns = [])
    {
        $list = empty($path) ? $this->form : data_get($this->form, $path, []);
        
        if (!is_array($list)) {
            $list = [];
        }

        if (!empty($columns)) {
            if (!empty($this->newItem)) {
                $newRow = [];
                foreach ($columns as $col) {
                    $newRow[$col] = $this->newItem[$col] ?? '';
                }
                $list[] = $newRow;
                $this->newItem = [];
            } else {
                $list[] = array_fill_keys($columns, '');
            }
        } else {
            $list[] = '';
        }

        if (empty($path)) {
            $this->form = $list;
        } else {
            data_set($this->form, $path, $list);
        }
    }

    public function addRowAndSave(string $path, array $columns = [])
    {
        $this->validateIfRules($this->rulesForNewItemFromColumns($columns));
        
        // Get current list to determine new index
        $list = empty($path) ? $this->form : data_get($this->form, $path, []);
        $newIndex = count(is_array($list) ? $list : []);
        
        // Add the row
        $this->addRow($path, $columns);
        
        // Move uploads from newItem to the new index
        if (isset($this->uploads['newItem']) && !empty($this->uploads['newItem'])) {
            if (empty($path)) {
                $this->uploads[$newIndex] = $this->uploads['newItem'];
            } else {
                data_set($this->uploads, $path . '.' . $newIndex, $this->uploads['newItem']);
            }
            unset($this->uploads['newItem']);
        }
        
        $this->save();
        $this->newItem = [];
        $this->dispatch('notify', type: 'success', message: 'Item added successfully!');
        $this->dispatch('close-modal');
    }

    public function removeRow(string $path, int $index)
    {
        $list = empty($path) ? $this->form : data_get($this->form, $path, []);
        
        if (!is_array($list)) {
            return;
        }

        unset($list[$index]);
        $reindexed = array_values($list);
        
        if (empty($path)) {
            $this->form = $reindexed;
        } else {
            data_set($this->form, $path, $reindexed);
        }
    }

    public function removeRowWithConfirm(string $path, int $index)
    {
        $this->removeRow($path, $index);
        $this->save();
        $this->dispatch('notify', type: 'success', message: 'Item removed successfully!');
    }

    public function confirmRemoveRow(string $path, int $index)
    {
        $this->dispatch('confirm-delete', 
            message: 'Are you sure you want to delete this item?',
            path: $path,
            index: $index
        );
    }

    private function applyUploads(array $data, array $uploads, string $prefix = ''): array
    {
        foreach ($uploads as $key => $value) {
            $path = $prefix ? "{$prefix}.{$key}" : (string) $key;

            if (is_array($value) && !($value instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile)) {
                $currentData = data_get($data, $path, []);
                if (is_array($currentData)) {
                    $updated = $this->applyUploads($currentData, $value, '');
                    data_set($data, $path, $updated);
                }
                continue;
            }

            if ($value instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                try {
                    $templatePath = $this->templateService->templatePath($this->templateKey);
                    $uploadsDir = $templatePath . DIRECTORY_SEPARATOR . 'uploads';
                    
                    // Create uploads directory if it doesn't exist
                    if (!File::exists($uploadsDir)) {
                        File::makeDirectory($uploadsDir, 0755, true);
                    }

                    // Generate unique filename
                    $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $value->getClientOriginalName());
                    $relativePath = 'uploads/' . $filename;
                    $fullPath = $uploadsDir . DIRECTORY_SEPARATOR . $filename;

                    // Move file from temp to template uploads directory
                    File::copy($value->getRealPath(), $fullPath);
                    
                    // Set relative path in data
                    data_set($data, $path, $relativePath);
                    
                    \Log::info('Template image uploaded', [
                        'template' => $this->templateKey,
                        'field' => $path,
                        'filename' => $filename,
                        'path' => $relativePath
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Template upload failed', [
                        'template' => $this->templateKey,
                        'field' => $path,
                        'error' => $e->getMessage()
                    ]);
                    session()->flash('error', 'Failed to upload image: ' . $e->getMessage());
                }
            }
        }

        return $data;
    }

    private function rulesForForm(): array
    {
        $rules = [];
        $this->buildRulesFromData($this->form, 'form', $rules);
        return $rules;
    }

    private function rulesForNewItemFromColumns(array $columns): array
    {
        $rules = [];
        foreach ($columns as $col) {
            if (preg_match('/^id$/i', $col)) {
                continue;
            }

            $ruleParts = $this->isImageKey($col) ? ['nullable'] : ['required'];
            if ($this->isNumericKey($col)) {
                $ruleParts[] = 'numeric';
            }
            $rules['newItem.' . $col] = implode('|', $ruleParts);
        }

        return $rules;
    }

    private function buildRulesFromData(array $data, string $prefix, array &$rules): void
    {
        foreach ($data as $key => $value) {
            $path = $prefix . '.' . $key;

            if (is_array($value)) {
                $this->buildRulesFromData($value, $path, $rules);
                continue;
            }

            $fieldKey = is_string($key) ? $key : '';
            
            // Don't validate image fields - they can be empty or contain file paths
            if ($this->isImageKey($fieldKey)) {
                continue;
            }
            
            $ruleParts = ['required'];
            if ($this->isNumericKey($fieldKey)) {
                $ruleParts[] = 'numeric';
            }

            $rules[$path] = implode('|', $ruleParts);
        }
    }

    private function isImageKey(string $key): bool
    {
        return preg_match('/(image|logo|banner|profile|photo|avatar|icon|bg|thumb|picture|background)/i', $key) === 1;
    }

    private function isNumericKey(string $key): bool
    {
        return preg_match('/(price|old_price|oldprice|amount|qty|quantity|total)/i', $key) === 1;
    }

    private function validateIfRules(array $rules): void
    {
        if (!empty($rules)) {
            $this->validate($rules);
        }
    }

    public function render()
    {
        return view('livewire.admin.template-visual-editor')
            ->layout('layouts.admin-livewire');
    }
}
