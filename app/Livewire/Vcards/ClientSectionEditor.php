<?php

namespace App\Livewire\Vcards;

use App\Models\Vcard;
use App\Repositories\Contracts\VcardContentRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Url;

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
    public array $editingItem = [];
    public ?int $editingIndex = null;
    public ?string $editingCategory = null;
    public bool $showIndex = false;
    public bool $subscriptionBlocked = false;
    public string $subscriptionMessage = 'Your subscription is inactive. Please contact support.';
    public array $categoryOptions = [];

    // Gallery single-file upload slot (used by rc-template gallery multi-add)
    public $galleryUploadFile = null;

    /** When true (opened via ?solo=1 sidebar link) the inner section nav is hidden. */
    #[Url]
    public bool $solo = false;

    public string $editMode = 'visual'; // 'visual' or 'code'
    public string $jsonContent = '';

    public function mount(string $subdomain, ?string $section = null): void
    {
        $this->vcard = $this->loadVcard($subdomain);
        $this->section = $section;

        if ($this->subscriptionBlocked) {
            return;
        }

        $data = $this->loadJson();

        // Bootstrap: ensure vcard data matches template defaults.
        // Handles: (1) missing sections, (2) empty section arrays, (3) list sections where template has more items.
        $tplBootstrap = $this->loadTemplateDefaultJson();
        foreach ($tplBootstrap as $tplKey => $tplVal) {
            if (!array_key_exists($tplKey, $data)) {
                // Section completely missing from vcard data → use template default
                $data[$tplKey] = $tplVal;
            } elseif (is_array($tplVal) && !empty($tplVal) && is_array($data[$tplKey])) {
                if (empty($data[$tplKey])) {
                    // Section exists but is empty array → use template default
                    $data[$tplKey] = $tplVal;
                }
                // NOTE: Do NOT merge template items into non-empty sections.
                // The count-comparison merge was re-adding items the user deliberately deleted.
            }
        }
        unset($tplBootstrap, $tplKey, $tplVal);

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
        $hiddenSections = [
            'files', 'floatingBar', 'floatBar', 'bottomBar',
            'labels', 'toast', 'share', 'shareModal',
            'banner', 'header', 'status', 'cart',
            'messages', // static WA/modal message templates — admin-only
        ];
        // For templates where 'sections' key is just heading texts, hide it from sidebar
        $templateKey2 = $this->vcard->template_key ?? '';
        if (!in_array($templateKey2, ['minimart-template'], true)) {
            $hiddenSections[] = 'sections';
        }
        // Template-specific hidden sections
        if ($templateKey2 === 'doctor-clinic-template') {
            $hiddenSections[] = 'promo';
        }
        if ($templateKey2 === 'restaurant-cafe-template') {
            $hiddenSections[] = 'qr';
        }
        $allSections = array_values(array_filter(array_keys($data), function ($key) use ($hiddenSections) {
            return $key === '_common' || (!str_starts_with($key, '_') && !in_array($key, $hiddenSections));
        }));
        $commonIdx = array_search('_common', $allSections);
        if ($commonIdx !== false && $commonIdx > 0) {
            array_splice($allSections, $commonIdx, 1);
            array_unshift($allSections, '_common');
        }

        // Coaching template: show only the allowed sections in a fixed order
        if ($templateKey2 === 'coaching-template') {
            $coachingAllowed = [
                '_common', 'meta', 'profile', 'counters', 'trust', 'director',
                'whyChoose', 'courses', 'batches', 'demo', 'fees', 'faculty',
                'materials', 'modes', 'faq', 'social', 'payment', 'messages',
            ];
            $allSections = array_values(array_filter($coachingAllowed, fn($s) => array_key_exists($s, $data)));
        }

        // Sweetshop template: show only the allowed sections in a fixed order
        if ($templateKey2 === 'sweetshop-template') {
            $sweetshopAllowed = [
                '_common', 'meta', 'assets', 'socialLinks', 'services',
                'categories', 'products', 'gallery', 'businessHours',
                'qr', 'paymentMethods', 'messages',
            ];
            $allSections = array_values(array_filter($sweetshopAllowed, fn($s) => array_key_exists($s, $data)));
        }

        // Doctor-clinic template: show only the allowed sections in a fixed order
        if ($templateKey2 === 'doctor-clinic-template') {
            $doctorAllowed = [
                '_common', 'meta', 'assets', 'specializations', 'appointment',
                'conditions', 'fees', 'hours', 'awards', 'social', 'payments', 'messages',
            ];
            $allSections = array_values(array_filter($doctorAllowed, fn($s) => array_key_exists($s, $data)));
        }

        // Append virtual _settings tab whenever section visibility config exists
        $this->sections = $allSections;
        if (!empty($this->sectionsConfig)) {
            $this->sections[] = '_settings';
        }

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

        // Re-index numeric-keyed arrays (handles both integer keys and string numeric keys from MongoDB)
        if (is_array($sectionData) && !empty($sectionData)) {
            $keys = array_keys($sectionData);
            if (isset($keys[0]) && is_numeric($keys[0])) {
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
        // _settings is a virtual display-only tab — nothing to save
        if ($this->section === '_settings') { return; }

        $this->resetValidation();
        $this->validateIfRules($this->rulesForForm());

        $payload = $this->sanitizeArrayKeys($this->applyUploads($this->form, $this->uploads));
        $data = $this->loadJson();
        $data[$this->section] = $payload;

        // Auto-generate category key from label/name when the categories section is saved
        if ($this->section === 'categories') {
            foreach ($payload as &$item) {
                if (is_array($item) && empty($item['key'])) {
                    $item['key'] = \Illuminate\Support\Str::slug($item['label'] ?? $item['name'] ?? '');
                }
            }
            unset($item);
            $data[$this->section] = $payload;
        }

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

        // Rebuild categoryOptions live so product dropdowns reflect new categories without page reload
        if (in_array($this->section, ['categories', 'profile', 'R'], true)) {
            $this->categoryOptions = $this->buildCategoryOptions($data);
        }

        $this->dispatch('notify', type: 'success', message: 'Changes saved successfully!');
        $this->dispatch('vcard-saved');
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

    /**
     * Fires when a gallery image is uploaded via the JS $wire.upload('galleryUploadFile', ...) API.
     * Stores the image, appends it as a new row, saves, and resets the slot.
     */
    public function updatedGalleryUploadFile(): void
    {
        if (!$this->galleryUploadFile) { return; }

        $path = $this->storeUploadedImage(
            $this->galleryUploadFile,
            'vcards/' . $this->vcard->subdomain . '/uploads'
        );
        $url = \Storage::url($path);

        $this->form   = array_values(is_array($this->form) ? $this->form : []);
        $this->form[] = ['image' => $url];

        $data                 = $this->loadJson();
        $data[$this->section] = $this->form;
        $this->storeJson($data);

        $this->galleryUploadFile = null;
        $this->dispatch('gallery-image-added');
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
        return 'nullable';
    }

    private function isSystemManagedKey(string $key): bool
    {
        return preg_match('/^(qr|vcard|qrcode|vcf)$/i', $key) === 1
            || preg_match('/(filename|file_name)$/i', $key) === 1;
    }

    private function isOptionalTextKey(string $key): bool
    {
        return preg_match('/(alt|subtitle|sub|note|description|desc|bio|tagline|color|tone|tag|badge|icon|class|gradient|stroke|suffix|prefix|initials|label|query|exp|stars|rating|count|per|old|save|dur|key|tc|veg|today|open|closed|rowClass|action|url|type|detail|slot|enabled|active|spec|brand|status)$/i', $key) === 1;
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

    public function reorderRow(string $path, int $from, int $to): void
    {
        if ($from === $to) {
            return;
        }
        if (empty($path)) {
            $list = $this->form;
        } else {
            $list = data_get($this->form, $path, []);
        }
        if (!is_array($list) || !isset($list[$from]) || !isset($list[$to])) {
            return;
        }
        $item = array_splice($list, $from, 1);
        array_splice($list, $to, 0, $item);
        $list = array_values($list);
        if (empty($path)) {
            $this->form = $list;
        } else {
            data_set($this->form, $path, $list);
        }
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

    public function openOfferModal(?int $index = null): void
    {
        $this->editingIndex = $index;
        if ($index !== null && isset($this->form[$index])) {
            $this->editingItem = $this->form[$index];
        } else {
            $this->editingItem = ['icon' => '🏷️', 'title' => '', 'tag' => '', 'desc' => ''];
        }
        $this->dispatch('open-offer-modal', wireId: $this->getId());
    }

    public function saveOfferModal(): void
    {
        if ($this->editingIndex !== null) {
            $this->form[$this->editingIndex] = $this->editingItem;
        } else {
            $this->form[] = $this->editingItem;
        }
        $this->save();
        $this->editingItem = [];
        $this->editingIndex = null;
        $this->dispatch('notify', type: 'success', message: 'Offer saved successfully!');
    }

    public function openPaymentModal(?int $index = null): void
    {
        $this->editingIndex = $index;
        if ($index !== null && isset($this->form[$index])) {
            $this->editingItem = $this->form[$index];
        } else {
            $this->editingItem = ['icon' => 'card', 'name' => '', 'sub' => '', 'stroke' => '#1565c0'];
        }
        $this->dispatch('open-payment-modal', wireId: $this->getId());
    }

    public function savePaymentModal(): void
    {
        if ($this->editingIndex !== null) {
            $this->form[$this->editingIndex] = $this->editingItem;
        } else {
            $this->form[] = $this->editingItem;
        }
        $this->save();
        $this->editingItem = [];
        $this->editingIndex = null;
        $this->dispatch('notify', type: 'success', message: 'Payment method saved!');
    }

    public function openItemModal(?int $index = null, array $defaultItem = []): void
    {
        $this->editingIndex = $index;
        if ($index !== null) {
            $this->editingItem = $this->form[$index] ?? $defaultItem;
        } else {
            $this->editingItem = $defaultItem;
        }
        $this->dispatch('open-item-modal', wireId: $this->getId());
    }

    public function saveItemModal(): void
    {
        $list  = is_array($this->form) ? $this->form : [];
        $index = $this->editingIndex ?? count($list);
        $list[$index] = $this->editingItem;
        $this->form   = array_values($list);

        if (!empty($this->uploads['itemEdit'])) {
            foreach ($this->uploads['itemEdit'] as $col => $file) {
                $this->uploads[$index][$col] = $file;
            }
            unset($this->uploads['itemEdit']);
        }

        $wasEdit = $this->editingIndex !== null;
        $this->save();
        $this->editingItem  = [];
        $this->editingIndex = null;
        $this->dispatch('hide-item-modal');
        $this->dispatch('notify', type: 'success', message: $wasEdit ? 'Item updated!' : 'Item added!');
    }

    public function openNestedItemModal(string $path, ?int $index, array $defaultItem = []): void
    {
        $this->editingIndex = $index;
        if ($index !== null) {
            $list = data_get($this->form, $path, []);
            $this->editingItem = $list[$index] ?? $defaultItem;
        } else {
            $this->editingItem = $defaultItem;
        }
        $this->dispatch('open-item-modal', wireId: $this->getId());
    }

    public function saveNestedItemModal(string $path): void
    {
        $list  = data_get($this->form, $path, []);
        if (!is_array($list)) { $list = []; }
        $index = $this->editingIndex ?? count($list);
        $list[$index] = $this->editingItem;
        data_set($this->form, $path, array_values($list));
        $wasEdit = $this->editingIndex !== null;
        $this->save();
        $this->editingItem  = [];
        $this->editingIndex = null;
        $this->dispatch('hide-item-modal');
        $this->dispatch('notify', type: 'success', message: $wasEdit ? 'Item updated!' : 'Item added!');
    }

    public function openMenuItemModal(string $category, ?int $index = null): void
    {
        $this->editingCategory = $category;
        $this->editingIndex    = $index;
        if ($index !== null && isset($this->form[$category][$index])) {
            $this->editingItem = $this->form[$category][$index];
        } else {
            $this->editingItem = [
                'id'            => '',
                'name'          => '',
                'icon'          => '',
                'desc'          => '',
                'price'         => '',
                'op'            => '',
                'tag'           => '',
                'tc'            => '',
                'product_image' => '',
                'veg'           => false,
            ];
        }
        $this->dispatch('open-menu-item-modal', wireId: $this->getId());
    }

    public function saveMenuItemModal(): void
    {
        $category = $this->editingCategory;
        if (empty($category)) {
            $this->dispatch('notify', type: 'warning', message: 'No category selected.');
            return;
        }
        $list = $this->form[$category] ?? [];
        if (!is_array($list)) { $list = []; }
        $index = $this->editingIndex ?? count($list);
        $list[$index] = $this->editingItem;
        $this->form[$category] = $list;
        if (!empty($this->uploads['menuItemEdit']['product_image'])) {
            if (!isset($this->uploads[$category])) { $this->uploads[$category] = []; }
            if (!isset($this->uploads[$category][$index])) { $this->uploads[$category][$index] = []; }
            $this->uploads[$category][$index]['product_image'] = $this->uploads['menuItemEdit']['product_image'];
            unset($this->uploads['menuItemEdit']);
        }
        $wasEdit = $this->editingIndex !== null;
        $this->save();
        $this->editingItem     = [];
        $this->editingIndex    = null;
        $this->editingCategory = null;
        $this->dispatch('hide-menu-item-modal');
        $this->dispatch('notify', type: 'success', message: $wasEdit ? 'Item updated!' : 'Item added to ' . $category . '!');
    }

    public function deleteMenuCategory(string $category): void
    {
        if (isset($this->form[$category])) {
            unset($this->form[$category]);
            $this->save();
            $this->dispatch('notify', type: 'success', message: "Category '{$category}' deleted.");
        }
    }

    public function addMenuCategory(): void
    {
        $name = trim($this->newItem['newCategoryName'] ?? '');
        if ($name === '') {
            return;
        }
        if (!isset($this->form[$name])) {
            $this->form[$name] = [];
        }
        $this->newItem['newCategoryName'] = '';
        $this->save();
        $this->dispatch('hide-category-modal');
        $this->dispatch('notify', type: 'success', message: "Category '{$name}' created.");
    }

    public function addStringAndSave(string $path, string $newItemKey): void
    {
        $value = trim($this->newItem[$newItemKey] ?? '');
        if ($value === '') {
            return;
        }
        $list = data_get($this->form, $path, []);
        if (!is_array($list)) {
            $list = [];
        }
        $list[] = $value;
        data_set($this->form, $path, $list);
        $this->newItem[$newItemKey] = '';
        $this->save();
    }

    public function switchToCodeEditor(): void
    {
        $data = $this->loadJson();
        $this->jsonContent = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $this->editMode = 'code';
        $this->showIndex = false;
        $this->dispatch('notify', type: 'info', message: 'Switched to code editor mode');
    }

    public function switchToVisualEditor(): void
    {
        $this->editMode = 'visual';
        $data = $this->loadJson();
        if (isset($data['_sections_config']) && is_array($data['_sections_config'])) {
            $this->sectionsConfig = $data['_sections_config'];
        }
        if (!$this->section || !in_array($this->section, $this->sections, true)) {
            $default = in_array('_common', $this->sections, true) ? '_common' : ($this->sections[0] ?? null);
            if ($default) {
                $this->selectSection($default);
            }
        }
        $this->dispatch('notify', type: 'info', message: 'Switched to visual editor mode');
    }

    public function saveCodeEditor(): void
    {
        $decoded = json_decode($this->jsonContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->dispatch('notify', type: 'error', message: 'Invalid JSON: ' . json_last_error_msg());
            return;
        }
        if (!is_array($decoded)) {
            $this->dispatch('notify', type: 'error', message: 'JSON must be an object/array');
            return;
        }
        $this->storeJson($decoded);
        if (isset($decoded['_sections_config']) && is_array($decoded['_sections_config'])) {
            $this->sectionsConfig = $decoded['_sections_config'];
        }
        $this->dispatch('notify', type: 'success', message: 'JSON saved successfully!');
        $this->dispatch('vcard-saved');
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
        $this->editMode = 'visual';
        $this->section  = $section;
        $this->uploads  = [];
        $this->newItem  = [];
        $this->dispatch('section-changed', section: $section);

        $data = $this->loadJson();

        // Bootstrap: fill section from template default if missing, empty, or template has newer items
        $tplDefault    = $this->loadTemplateDefaultJson();
        $tplSectionVal = $tplDefault[$section] ?? null;
        if (!array_key_exists($section, $data)) {
            $data[$section] = $tplSectionVal ?? [];
        } elseif ($tplSectionVal !== null && is_array($tplSectionVal) && !empty($tplSectionVal) && is_array($data[$section])) {
            if (empty($data[$section])) {
                $data[$section] = $tplSectionVal;
            }
            // NOTE: Do NOT merge template items into non-empty sections.
            // The count-comparison merge was re-adding items the user deliberately deleted.
        }

        $sectionData = $data[$section] ?? [];

        // Re-index numeric-keyed arrays (handles both integer keys and string numeric keys from MongoDB)
        if (is_array($sectionData) && !empty($sectionData)) {
            $keys = array_keys($sectionData);
            if (isset($keys[0]) && is_numeric($keys[0])) {
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
                    $path = $this->storeUploadedImage($value, 'vcards/' . $this->vcard->subdomain . '/uploads');
                    // Always store as a plain root-relative path.
                    // Templates that need the CSS url() wrapper apply it at render time.
                    $payload[$key] = '/storage/' . $path;
                } catch (\Exception $e) {
                    \Log::error('File upload error: ' . $e->getMessage());
                }
            }
        }

        return $payload;
    }

    /**
     * Store an uploaded image, resizing and compressing it to max 1920px / JPEG 75% if oversized.
     * Falls back to the standard Livewire store() on any GD failure.
     */
    private function storeUploadedImage($file, string $directory): string
    {
        $realPath  = $file->getRealPath();
        $imageInfo = @getimagesize($realPath);

        if (!$imageInfo) {
            return $file->store($directory, 'public');
        }

        [$origWidth, $origHeight, $imageType] = $imageInfo;
        $maxDim   = 1200;

        $src = match ($imageType) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($realPath),
            IMAGETYPE_PNG  => @imagecreatefrompng($realPath),
            IMAGETYPE_WEBP => @imagecreatefromwebp($realPath),
            IMAGETYPE_GIF  => @imagecreatefromgif($realPath),
            default        => null,
        };

        if (!$src) {
            return $file->store($directory, 'public');
        }

        $scale  = min($maxDim / $origWidth, $maxDim / $origHeight, 1.0);
        $newW   = (int) round($origWidth * $scale);
        $newH   = (int) round($origHeight * $scale);
        $dst    = imagecreatetruecolor($newW, $newH);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origWidth, $origHeight);
        imagedestroy($src);

        $tmpFile = tempnam(sys_get_temp_dir(), 'vcimg_') . '.jpg';
        imagejpeg($dst, $tmpFile, 75);
        imagedestroy($dst);

        $storedPath = $directory . '/' . uniqid('img_', true) . '.jpg';
        \Storage::disk('public')->put($storedPath, file_get_contents($tmpFile));
        @unlink($tmpFile);

        return $storedPath;
    }

    /**
     * Merge new items from template defaults into an existing vcard list section.
     * Only items whose unique identifier (key/id/slug/name/title/day) is absent from the
     * vcard list are appended — existing vcard items are never overwritten.
     */
    private function mergeListItems(array $existing, array $defaults): array
    {
        $sample  = $defaults[0] ?? [];
        $idField = null;
        foreach (['key', 'id', 'slug', 'name', 'title', 'day'] as $candidate) {
            if (array_key_exists($candidate, $sample)) {
                $idField = $candidate;
                break;
            }
        }

        if ($idField === null) {
            // No reliable identifier — append items past the existing count
            return array_merge($existing, array_slice($defaults, count($existing)));
        }

        $existingIds = array_column($existing, $idField);
        $result      = $existing;
        foreach ($defaults as $item) {
            $itemId = $item[$idField] ?? null;
            if ($itemId !== null && !in_array($itemId, $existingIds, true)) {
                $result[] = $item;
            }
        }
        return $result;
    }

    /**
     * Recursively sanitize array keys:
     * - Mixed int+string keys → strip string-keyed entries, re-index integer ones
     * - All-integer keys     → ensure sequential 0-based re-indexing
     * - All-string keys      → recurse into values only
     */
    private function sanitizeArrayKeys(array $data): array
    {
        $keys    = array_keys($data);
        $intKeys = array_filter($keys, 'is_int');
        $strKeys = array_filter($keys, fn($k) => !is_int($k));

        if (!empty($intKeys) && !empty($strKeys)) {
            $data = array_values(array_filter($data, fn($k) => is_int($k), ARRAY_FILTER_USE_KEY));
        } elseif (!empty($intKeys)) {
            $data = array_values($data);
        }

        foreach ($data as $k => $v) {
            if (is_array($v)) {
                $data[$k] = $this->sanitizeArrayKeys($v);
            }
        }

        return $data;
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
