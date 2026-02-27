<?php

namespace App\Livewire\Vcards;

use App\Models\Vcard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
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
        
        // Filter out metadata keys (those starting with _)
        $this->sections = array_filter(array_keys($data), function ($key) {
            return !str_starts_with($key, '_');
        });

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

        $payload = $this->applyUploads($this->form, $this->uploads);
        $data = $this->loadJson();
        $data[$this->section] = $payload;

        $this->storeJson($data);
        $this->form = $payload;
        $this->uploads = [];

        session()->flash('success', 'vCard data updated.');
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

            $ruleParts = $this->isImageKey($col) ? ['nullable'] : ['required'];
            if ($this->isNumericKey($col)) {
                $ruleParts[] = 'numeric';
            }
            $rules['newItem.' . $col] = implode('|', $ruleParts);
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
            $ruleParts = $this->isImageKey($fieldKey) ? ['nullable'] : ['required'];
            if ($this->isNumericKey($fieldKey)) {
                $ruleParts[] = 'numeric';
            }

            $rules[$path] = implode('|', $ruleParts);
        }
    }

    private function ruleForProperty(string $propertyName): ?string
    {
        $segments = explode('.', $propertyName);
        $fieldKey = end($segments);
        if (!is_string($fieldKey) || $fieldKey === '') {
            return null;
        }

        $ruleParts = $this->isImageKey($fieldKey) ? ['nullable'] : ['required'];
        if ($this->isNumericKey($fieldKey)) {
            $ruleParts[] = 'numeric';
        }

        return implode('|', $ruleParts);
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
        $dataPath = $this->vcard->data_path;
        if ($dataPath && Storage::disk('public')->exists($dataPath)) {
            $raw = Storage::disk('public')->get($dataPath);
            $data = json_decode($raw, true);
            if (is_array($data)) {
                return $data;
            }
        }

        $storageRoot = config('vcard.storage_root');
        $fallbackPath = $storageRoot . '/' . $this->vcard->subdomain . '/template/default.json';
        if (Storage::disk('public')->exists($fallbackPath)) {
            $raw = Storage::disk('public')->get($fallbackPath);
            $data = json_decode($raw, true);
            if (is_array($data)) {
                return $data;
            }
        }

        $templateRoot = config('vcard.template_root');
        $filePath = $templateRoot . DIRECTORY_SEPARATOR . $this->vcard->template_key . DIRECTORY_SEPARATOR . 'default.json';
        if ($templateRoot && File::exists($filePath)) {
            $raw = File::get($filePath);
            $data = json_decode($raw, true);
            if (is_array($data)) {
                return $data;
            }
        }

        return [];
    }

    private function storeJson(array $payload): void
    {
        $json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $storageRoot = config('vcard.storage_root');

        $dataPath = $this->vcard->data_path ?: $storageRoot . '/' . $this->vcard->subdomain . '/data.json';
        Storage::disk('public')->put($dataPath, $json);

        $templateDefault = $storageRoot . '/' . $this->vcard->subdomain . '/template/default.json';
        Storage::disk('public')->put($templateDefault, $json);

        $this->vcard->update([
            'data_path' => $dataPath,
        ]);
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

    public function render()
    {
        return view('livewire.vcards.client-section-editor', [
            'baseDomain' => config('vcard.base_domain'),
        ])->layout('layouts.vcard-editor');
    }
}
