<?php

namespace App\Livewire\Vcards;

use App\Models\Vcard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithFileUploads;

class ClientSectionEditor extends Component
{
    use WithFileUploads;

    public Vcard $vcard;
    public string $section = '';
    public array $sections = [];
    public array $form = [];
    public array $uploads = [];
    public bool $showIndex = false;

    public function mount(string $subdomain, string $section = null): void
    {
        $this->vcard = $this->loadVcard($subdomain);
        $data = $this->loadJson();
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
            // If keys are numeric strings starting from 0 and sequential, convert to indexed array
            if (isset($keys[0]) && is_numeric($keys[0]) && $keys === range(0, count($keys) - 1)) {
                $sectionData = array_values($sectionData);
            }
        }
        
        $this->form = $sectionData;
    }

    public function save(): void
    {
        $payload = $this->applyUploads($this->form, $this->uploads);
        $data = $this->loadJson();
        $data[$this->section] = $payload;

        $this->storeJson($data);
        $this->uploads = [];

        session()->flash('success', 'vCard data updated.');
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
            $list[] = array_fill_keys($columns, '');
        } else {
            $list[] = '';
        }

        if (empty($path)) {
            $this->form = $list;
        } else {
            data_set($this->form, $path, $list);
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

    public function removeRowWithConfirm(string $path, int $index): void
    {
        $this->removeRow($path, $index);
        $this->save();
        session()->flash('success', 'Item deleted successfully.');
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

            if ($value && method_exists($value, 'isValid') && $value->isValid()) {
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
