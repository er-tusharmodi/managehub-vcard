<?php

namespace App\Livewire\Vcards;

use App\Models\Vcard;
use App\Repositories\Contracts\VcardContentRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class ClientSectionEditor extends Component
{
    use WithFileUploads;

    public Vcard $vcard;
    public ?string $section = null;
    public array $sections = [];
    public array $sectionsConfig = [];
    public array $form = [];
    public array $uploads = [];
    public array $newItem = [];
    public bool $showIndex = false;
    public bool $subscriptionBlocked = false;
    public string $subscriptionMessage = 'Your subscription is inactive. Please contact support.';
    public array $categoryOptions = [];

    public function mount(string $subdomain, ?string $section = null): void
    {
        $this->vcard = $this->loadVcard($subdomain);
        $this->section = $section;

        if ($this->subscriptionBlocked) {
            return;
        }

        $data = $this->loadJson();
        $this->categoryOptions = $this->buildCategoryOptions($data);
        
        // Load sections config if available
        if (isset($data['_sections_config']) && is_array($data['_sections_config'])) {
            $this->sectionsConfig = $data['_sections_config'];
        }

        // Bootstrap _common for existing vCards that don't have it yet
        if (!isset($data['_common'])) {
            $tplJson = $this->loadTemplateDefaultJson();
            $commonFieldCfg = $tplJson['_field_config']['_common'] ?? [];
            if (!empty($commonFieldCfg)) {
                $common = [];
                foreach ($commonFieldCfg as $key => $cfg) {
                    $syncPaths = $cfg['sync'] ?? [];
                    $primaryPath = $syncPaths[0] ?? null;
                    if ($primaryPath) {
                        $parts = explode('.', $primaryPath, 2);
                        if (count($parts) === 2 && isset($data[$parts[0]][$parts[1]])) {
                            $common[$key] = $data[$parts[0]][$parts[1]];
                        }
                    } else {
                        // No sync path — initialize with type-appropriate default
                        $common[$key] = ($cfg['type'] ?? 'text') === 'list' ? [] : '';
                    }
                }
                if (!empty($common)) {
                    $data['_common'] = $common;
                }
            }
        } else {
            // _common exists — fill in any NEW fields added to _field_config._common since last bootstrap
            $tplJson = $this->loadTemplateDefaultJson();
            $commonFieldCfg = $tplJson['_field_config']['_common'] ?? [];
            foreach ($commonFieldCfg as $key => $cfg) {
                if (!array_key_exists($key, $data['_common'])) {
                    $syncPaths = $cfg['sync'] ?? [];
                    $primaryPath = $syncPaths[0] ?? null;
                    if ($primaryPath) {
                        $parts = explode('.', $primaryPath, 2);
                        $data['_common'][$key] = (count($parts) === 2 && isset($data[$parts[0]][$parts[1]]))
                            ? $data[$parts[0]][$parts[1]] : '';
                    } else {
                        $data['_common'][$key] = ($cfg['type'] ?? 'text') === 'list' ? [] : '';
                    }
                }
            }
        }
        $allSections = array_values(array_filter(array_keys($data), function ($key) {
            if ($key === 'files') return false;
            return $key === '_common' || !str_starts_with($key, '_');
        }));
        $commonIdx = array_search('_common', $allSections);
        if ($commonIdx !== false && $commonIdx > 0) {
            array_splice($allSections, $commonIdx, 1);
            array_unshift($allSections, '_common');
        }
        $this->sections = $allSections;

        if ($section === null) {
            $section = in_array('_common', $this->sections, true) ? '_common' : ($this->sections[0] ?? null);
            if ($section === null) {
                $this->showIndex = true;
                return;
            }
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
            // If keys are numeric strings starting from 0 and sequential, convert to indexed array
            if (isset($keys[0]) && is_numeric($keys[0]) && $keys === range(0, count($keys) - 1)) {
                $sectionData = array_values($sectionData);
            }
        }
        
        $this->form = $sectionData;
    }

    public function save(): void
    {
        if ($this->subscriptionBlocked) {
            session()->flash('error', $this->subscriptionMessage);
            return;
        }

        $this->resetValidation();
        $this->validateIfRules($this->rulesForForm());

        $payload = $this->applyUploads($this->form, $this->uploads);
        $data = $this->loadJson();
        $data[$this->section] = $payload;

        // Sync _common fields to their mapped paths in other sections
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

        $this->storeJson($data);
        $this->form = $payload;
        $this->uploads = [];

        $this->dispatch('notify', type: 'success', message: 'Changes saved successfully!');
    }

    public function saveAndNotify(): void
    {
        if ($this->subscriptionBlocked) {
            $this->dispatch('notify', type: 'error', message: $this->subscriptionMessage);
            return;
        }

        $this->validateIfRules($this->rulesForForm());
        $this->save();
        $this->dispatch('notify', type: 'success', message: 'Changes saved successfully!');
        $this->dispatch('close-modal');
    }

    public function updated(string $propertyName): void
    {
        if (str_starts_with($propertyName, 'uploads.')) {
            return;
        }

        $rules = $this->rulesForAll();
        if (!isset($rules[$propertyName])) {
            $rule = $this->ruleForProperty($propertyName);
            if ($rule !== null) {
                $rules[$propertyName] = $rule;
            }
        }

        if (!empty($rules)) {
            $this->validateOnly($propertyName, $rules);
        }
    }

    public function addRow(string $path, array $columns = []): void
    {
        if (empty($path)) {
            $list = $this->form;
        } else {
            $list = data_get($this->form, $path, []);
        }
        
        if (!is_array($list)) {
            $list = [];
        }

        if (!empty($columns)) {
            // Use newItem data if available, otherwise create empty row
            if (!empty($this->newItem)) {
                $newRow = [];
                foreach ($columns as $col) {
                    $newRow[$col] = $this->newItem[$col] ?? '';
                }
                $list[] = $newRow;
                $this->newItem = []; // Clear for next add
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

    public function addRowAndSave(string $path, array $columns = []): void
    {
        $this->validateIfRules($this->rulesForNewItemFromColumns($columns));
        // Get the current list to determine the new index
        if (empty($path)) {
            $list = $this->form;
        } else {
            $list = data_get($this->form, $path, []);
        }
        
        $newIndex = count(is_array($list) ? $list : []);
        
        // Add the row
        $this->addRow($path, $columns);
        
        // Move uploads from newItem to the new index in the correct path
        if (isset($this->uploads['newItem']) && !empty($this->uploads['newItem'])) {
            if (empty($path)) {
                // Root level array: uploads[newIndex] = uploads.newItem
                $this->uploads[$newIndex] = $this->uploads['newItem'];
            } else {
                // Nested array: uploads[path][newIndex] = uploads.newItem
                data_set($this->uploads, $path . '.' . $newIndex, $this->uploads['newItem']);
            }
            unset($this->uploads['newItem']);
        }
        
        $this->save();
        $this->newItem = []; // Clear the form
        $this->dispatch('notify', type: 'success', message: 'Item added successfully!');
        $this->dispatch('close-modal');
    }

    private function rulesForAll(): array
    {
        return array_merge($this->rulesForForm(), $this->rulesForNewItem());
    }

    private function rulesForForm(): array
    {
        $rules = [];
        $this->buildRulesFromData($this->form, 'form', $rules);
        return $rules;
    }

    private function rulesForNewItem(): array
    {
        $rules = [];
        $this->buildRulesFromData($this->newItem, 'newItem', $rules);
        return $rules;
    }

    private function rulesForNewItemFromColumns(array $columns): array
    {
        $rules = [];
        foreach ($columns as $col) {
            if (preg_match('/^id$/i', $col)) {
                continue;
            }

            $rules['newItem.' . $col] = $this->ruleStringForField($col);
        }

        return $rules;
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
                $key = Str::slug($label);
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

    private function buildRulesFromData(array $data, string $prefix, array &$rules): void
    {
        foreach ($data as $key => $value) {
            $path = $prefix . '.' . $key;

            if (is_array($value)) {
                $this->buildRulesFromData($value, $path, $rules);
                continue;
            }

            $fieldKey = is_string($key) ? $key : '';
            $rule = $this->buildRuleForField($fieldKey);
            if ($rule !== null) {
                $rules[$path] = $rule;
            }
        }
    }

    private function ruleForProperty(string $propertyName): ?string
    {
        $segments = explode('.', $propertyName);
        $fieldKey = end($segments);
        if (!is_string($fieldKey) || $fieldKey === '') {
            return null;
        }

        return $this->buildRuleForField($fieldKey);
    }

    private function buildRuleForField(string $fieldKey): ?string
    {
        if ($fieldKey === '') {
            return null;
        }

        return $this->ruleStringForField($fieldKey);
    }

    private function ruleStringForField(string $fieldKey): string
    {
        // Image, price/amount, system-managed, and known optional fields are nullable
        if ($this->isImageKey($fieldKey)
            || $this->isNumericKey($fieldKey)
            || $this->isSystemManagedKey($fieldKey)
            || $this->isOptionalTextKey($fieldKey)) {
            return 'nullable';
        }
        return 'required';
    }

    private function isSystemManagedKey(string $key): bool
    {
        return preg_match('/^(qr|vcard|qrcode|vcf)$/i', $key) === 1
            || preg_match('/(filename|file_name)$/i', $key) === 1;
    }

    private function isOptionalTextKey(string $key): bool
    {
        return preg_match('/(alt|subtitle|sub|note|description|desc|bio|tagline)$/i', $key) === 1;
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
        $templateKey = $this->vcard->template_key ?? null;
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

    public function removeRow(string $path, int $index): void
    {
        if (empty($path)) {
            $list = $this->form;
        } else {
            $list = data_get($this->form, $path, []);
        }
        
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

    public function moveRow(string $path, int $index, int $direction): void
    {
        if (empty($path)) {
            $list = $this->form;
        } else {
            $list = data_get($this->form, $path, []);
        }
        
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
        
        // Save changes to JSON file
        $this->save();
    }

    public function confirmRemoveRow(string $path, int $index): void
    {
        $this->dispatch(
            'confirm-delete',
            id: $this->getId(),
            index: $index,
            path: $path,
            method: 'removeRowWithConfirm',
            message: 'Are you sure you want to delete this item?'
        );
    }

    public function removeRowWithConfirm(int $index, string $path = ''): void
    {
        $this->removeRow($path, $index);
        $this->save();
        $this->dispatch('notify', type: 'success', message: 'Item deleted successfully!');
    }

    public function selectSection(string $section): void
    {
        if ($this->subscriptionBlocked) {
            $this->dispatch('notify', type: 'error', message: $this->subscriptionMessage);
            return;
        }
        if (!in_array($section, $this->sections, true)) {
            return;
        }
        $this->section  = $section;
        $this->uploads  = [];
        $this->newItem  = [];

        $data = $this->loadJson();
        $sectionData = $data[$section] ?? [];
        if (is_array($sectionData) && !empty($sectionData)) {
            $keys = array_keys($sectionData);
            if (isset($keys[0]) && is_numeric($keys[0]) && $keys === range(0, count($keys) - 1)) {
                $sectionData = array_values($sectionData);
            }
        }
        $this->form = $sectionData;
    }

    public function toggleSection(string $section): void
    {
        if ($this->subscriptionBlocked) {
            $this->dispatch('notify', type: 'error', message: $this->subscriptionMessage);
            return;
        }

        $data = $this->loadJson();

        if (!isset($data['_sections_config'][$section])) {
            $this->dispatch('notify', type: 'error', message: 'Section configuration not found.');
            return;
        }

        // Toggle the enabled status
        $data['_sections_config'][$section]['enabled'] = !$data['_sections_config'][$section]['enabled'];

        $this->storeJson($data);

        $status = $data['_sections_config'][$section]['enabled'] ? 'enabled' : 'disabled';
        $this->dispatch('notify', type: 'success', message: "Section {$status} successfully!");
        $this->dispatch('section-toggled');
    }

    private function loadVcard(string $subdomain): Vcard
    {
        $vcard = Vcard::where('subdomain', $subdomain)->firstOrFail();

        $user = Auth::user();
        $isAdmin = false;

        if ($user instanceof \App\Models\User && method_exists($user, 'hasRole')) {
            $isAdmin = $user->hasRole('admin');
        }

        if (!$isAdmin && $vcard->user_id && Auth::id() !== $vcard->user_id) {
            abort(403);
        }

        if (!$isAdmin && !$vcard->isSubscriptionActive()) {
            $this->subscriptionBlocked = true;
            if ($vcard->subscription_expires_at) {
                $this->subscriptionMessage = 'Your subscription expired on ' . $vcard->subscription_expires_at->format('d M Y') . '.';
            }
        }

        return $vcard;
    }

    private function loadJson(): array
    {
        return app(VcardContentRepository::class)->load($this->vcard);
    }

    private function storeJson(array $payload): void
    {
        app(VcardContentRepository::class)->save($this->vcard, $payload);
    }

    private function applyUploads(array $payload, array $uploads): array
    {
        foreach ($uploads as $key => $value) {
            if (is_array($value)) {
                $payload[$key] = $this->applyUploads($payload[$key] ?? [], $value);
                continue;
            }

            // Skip null, empty, or non-file values
            if (empty($value)) {
                continue;
            }

            // Check if it's a Livewire TemporaryUploadedFile or Laravel UploadedFile
            if (method_exists($value, 'isValid')) {
                if (!$value->isValid()) {
                    continue;
                }
                
                try {
                    $path = $value->store('vcards/' . $this->vcard->subdomain . '/uploads', 'public');
                    $newUrl = Storage::disk('public')->url($path);
                    
                    // Check if original value had url('...') wrapper format
                    $originalValue = $payload[$key] ?? '';
                    if (is_string($originalValue) && preg_match('/^url\([\'"]?.+[\'"]?\)$/i', $originalValue)) {
                        // Preserve the url() wrapper format
                        $payload[$key] = "url('" . $newUrl . "')";
                    } else {
                        $payload[$key] = $newUrl;
                    }
                } catch (\Exception $e) {
                    \Log::error('File upload error: ' . $e->getMessage());
                }
            }
        }

        return $payload;
    }

    private function loadTemplateDefaultJson(): array
    {
        $templateKey = $this->vcard->template_key ?? '';
        if (!$templateKey) return [];
        $path = base_path("vcard-template/{$templateKey}/default.json");
        if (!file_exists($path)) return [];
        return json_decode(file_get_contents($path), true) ?? [];
    }

    public function render()
    {
        // Load field config — prefer vCard data, fallback to template default
        $fieldConfig = [];
        if ($this->section) {
            $data = $this->loadJson();
            if (isset($data['_field_config'][$this->section])) {
                $fieldConfig = $data['_field_config'][$this->section];
            } else {
                $tplJson = $this->loadTemplateDefaultJson();
                $fieldConfig = $tplJson['_field_config'][$this->section] ?? [];
            }
            if (!empty($fieldConfig)) {
                $GLOBALS['_field_config'] = $fieldConfig;
            } else {
                unset($GLOBALS['_field_config']);
            }
        }
        $GLOBALS['_current_section'] = $this->section;

        return view('livewire.vcards.client-section-editor', [
            'baseDomain' => config('vcard.base_domain'),
            'fieldConfig' => $fieldConfig,
        ])->layout('layouts.vcard-editor');
    }
}
