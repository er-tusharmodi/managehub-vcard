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
    public array $sectionsConfig = [];
    public array $form = [];
    public array $uploads = [];
    public array $newItem = [];
    public array $categoryOptions = [];

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

        try {
            $data = $this->templateService->getTemplateDefaultJson($templateKey);

            // Build section list — exclude meta/system keys and entirely-static sections
            $excluded = [
                '_field_config', 'files', '_sections_config',
                'floatingBar', 'floatBar', 'bottomBar', 'cart',
                'footer', 'labels', 'toast', 'share', 'shareModal',
                'banner', 'header', 'status',
            ];
            // 'sections' key is heading texts in most templates (not minimart)
            if ($templateKey !== 'minimart-template') {
                $excluded[] = 'sections';
            }
            // Template-specific hidden sections
            $templateHide = [
                'doctor-clinic-template'    => ['profile', 'qr', 'promo'],
                'coaching-template'         => ['stats'],
                'electronics-shop-template' => ['repair', 'repairServices', 'promo'],
                'mens-salon-template'       => ['promo'],
            ];
            if (isset($templateHide[$templateKey])) {
                $excluded = array_merge($excluded, $templateHide[$templateKey]);
            }
            $allSections = array_values(array_filter(array_keys($data), fn($k) => !in_array($k, $excluded)));

            // Move _common to front
            $commonIdx = array_search('_common', $allSections);
            if ($commonIdx !== false && $commonIdx > 0) {
                array_splice($allSections, $commonIdx, 1);
                array_unshift($allSections, '_common');
            }
            $this->sections = $allSections;

            // Load sections config (for inline toggles)
            $this->sectionsConfig = $data['_sections_config'] ?? [];

            // Default to _common or first section
            $target = $section ?? '_common';
            if (!in_array($target, $this->sections, true)) {
                $target = $this->sections[0] ?? 'meta';
            }
            $this->section = $target;
            $this->loadFormForSection($this->section, $data);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->route('admin.templates.index');
        }
    }

    public function selectSection(string $section): void
    {
        if (!in_array($section, $this->sections, true)) {
            return;
        }
        $this->section = $section;
        $this->uploads = [];
        $this->newItem = [];
        $this->dispatch('section-changed', section: $section);
        try {
            $data = $this->templateService->getTemplateDefaultJson($this->templateKey);
            $this->loadFormForSection($section, $data);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function toggleSection(string $sectionKey): void
    {
        try {
            $data = $this->templateService->getTemplateDefaultJson($this->templateKey);
            if (!isset($data['_sections_config'][$sectionKey])) {
                return;
            }
            $data['_sections_config'][$sectionKey]['enabled'] = !($data['_sections_config'][$sectionKey]['enabled'] ?? true);
            $this->sectionsConfig = $data['_sections_config'];
            $this->backupService->backup($this->templateKey);
            $this->templateService->updateTemplateDefaultJson($this->templateKey, $data);
            $state = $data['_sections_config'][$sectionKey]['enabled'] ? 'enabled' : 'disabled';
            $this->dispatch('notify', type: 'success', message: ucfirst($sectionKey) . ' section ' . $state . '.');
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Failed to toggle section: ' . $e->getMessage());
        }
    }

    private function loadFormForSection(string $section, array $data): void
    {
        $sectionData = $data[$section] ?? [];

        // Re-index numeric-keyed arrays (handles both integer keys and string numeric keys)
        if (is_array($sectionData) && !empty($sectionData)) {
            $keys = array_keys($sectionData);
            if (isset($keys[0]) && is_numeric($keys[0])) {
                $sectionData = array_values($sectionData);
            }
        }
        $this->form = $sectionData;
        $this->categoryOptions = $this->buildCategoryOptions($data);
    }

    /**
     * Recursively sanitize array keys:
     * - Mixed int+string keys → strip string-keyed entries, re-index integer ones
     * - All-integer keys     → ensure sequential 0-based re-indexing
     * - All-string keys      → recurse into values only
     * Prevents stray keys (e.g. from Livewire wire state or partial-save bugs) from
     * corrupting list-type sections in template default.json.
     */
    private function sanitizeArrayKeys(array $data): array
    {
        $keys    = array_keys($data);
        $intKeys = array_filter($keys, 'is_int');
        $strKeys = array_filter($keys, fn($k) => !is_int($k));

        if (!empty($intKeys) && !empty($strKeys)) {
            // Mixed: remove string-keyed entries, re-index integer ones
            $data = array_values(array_filter($data, fn($k) => is_int($k), ARRAY_FILTER_USE_KEY));
        } elseif (!empty($intKeys)) {
            // All-integer: ensure clean sequential keys
            $data = array_values($data);
        }

        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $data[$k] = $this->sanitizeArrayKeys($v);
            }
        }

        return $data;
    }

    private function buildCategoryOptions(array $data): array
    {
        $rawCategories = $data['categories'] ?? [];
        if (!is_array($rawCategories)) {
            return [];
        }

        $options = [];
        foreach ($rawCategories as $category) {
            if (!is_array($category)) {
                continue;
            }

            $key = $category['key'] ?? null;
            $label = $category['label'] ?? $category['name'] ?? $category['query'] ?? $key;

            if (!$key && $label) {
                $key = \Illuminate\Support\Str::slug($label);
            }

            if (!$key || $key === 'all') {
                continue;
            }

            $options[$key] = $label ?? $key;
        }

        return collect($options)
            ->map(fn ($label, $key) => ['key' => $key, 'label' => $label])
            ->values()
            ->toArray();
    }

    public function save()
    {
        try {
            $payload = $this->sanitizeArrayKeys($this->applyUploads($this->form, $this->uploads));
            $data = $this->templateService->getTemplateDefaultJson($this->templateKey);
            $data[$this->section] = $payload;

            // Sync _common fields to their mapped paths
            if ($this->section === '_common') {
                $fieldConfigs = $data['_field_config']['_common'] ?? [];
                foreach ($payload as $key => $val) {
                    $syncPaths = $fieldConfigs[$key]['sync'] ?? [];
                    foreach ($syncPaths as $dotPath) {
                        $parts = explode('.', $dotPath, 2);
                        if (count($parts) === 2 && isset($data[$parts[0]]) && is_array($data[$parts[0]])) {
                            $data[$parts[0]][$parts[1]] = $val;
                        }
                    }
                }
            }

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

    public function addStringAndSave(string $path, string $key)
    {
        $value = $this->newItem[$key] ?? '';
        $list  = empty($path) ? $this->form : data_get($this->form, $path, []);
        if (!is_array($list)) {
            $list = [];
        }
        $list[] = $value;
        if (empty($path)) {
            $this->form = $list;
        } else {
            data_set($this->form, $path, $list);
        }
        $this->save();
        $this->newItem = [];
        $this->dispatch('notify', type: 'success', message: 'Item added successfully!');
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

    public function removeRowWithConfirm(int $index, string $path = '')
    {
        $this->removeRow($path, $index);
        $this->save();
        $this->dispatch('notify', type: 'success', message: 'Item removed successfully!');
    }

    public function moveRow(string $path, int $index, int $direction): void
    {
        $list = empty($path) ? $this->form : data_get($this->form, $path, []);

        if (!is_array($list)) {
            return;
        }

        $target = $index + $direction;
        if (!isset($list[$target])) {
            return;
        }

        [$list[$index], $list[$target]] = [$list[$target], $list[$index]];

        if (empty($path)) {
            $this->form = $list;
        } else {
            data_set($this->form, $path, $list);
        }

        $this->save();
    }

    public function confirmRemoveRow(string $path, int $index)
    {
        $this->dispatch(
            'confirm-delete',
            id: $this->getId(),
            message: 'Are you sure you want to delete this item?',
            path: $path,
            index: $index,
            method: 'removeRowWithConfirm'
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

                    // Store as absolute path so previews work across all contexts
                    // without needing $assetBaseUrl to be threaded through partials.
                    // Uses root-relative path so it works on any domain.
                    $absolutePath = '/template-assets/' . $this->templateKey . '/' . $relativePath;
                    data_set($data, $path, $absolutePath);
                    
                    \Log::info('Template image uploaded', [
                        'template' => $this->templateKey,
                        'field' => $path,
                        'filename' => $filename,
                        'path' => $absolutePath,
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

            $ruleParts = ['nullable'];
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

            $ruleParts = ['nullable'];
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

    public function resolveFormPartial(): ?string
    {
        if (!$this->section) {
            return null;
        }
        $templateKey = $this->templateKey ?? null;
        if (!$templateKey) {
            return null;
        }

        // Template-specific partial takes priority
        $templateView = 'livewire.vcards.forms.' . $templateKey . '.' . $this->section;
        if (view()->exists($templateView)) {
            return $templateView;
        }

        // Fall back to shared partial
        $sharedView = 'livewire.vcards.forms._shared.' . $this->section;
        if (view()->exists($sharedView)) {
            return $sharedView;
        }

        return null;
    }

    private function validateIfRules(array $rules): void
    {
        if (!empty($rules)) {
            $this->validate($rules);
        }
    }

    public function render()
    {
        $fieldConfig = [];
        if ($this->section && $this->templateKey) {
            try {
                $data = $this->templateService->getTemplateDefaultJson($this->templateKey);
                if (isset($data['_field_config'][$this->section])) {
                    $fieldConfig = $data['_field_config'][$this->section];
                    $GLOBALS['_field_config'] = $fieldConfig;
                } else {
                    unset($GLOBALS['_field_config']);
                }
            } catch (\Exception $e) {
                unset($GLOBALS['_field_config']);
            }
        }
        $GLOBALS['_current_section'] = $this->section;
        return view('livewire.admin.template-visual-editor', [
            'fieldConfig' => $fieldConfig,
        ])->layout('layouts.admin-livewire');
    }
}
