<?php

namespace App\Repositories\Sql;

use App\Models\Vcard;
use App\Repositories\Contracts\VcardContentRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SqlVcardContentRepository implements VcardContentRepository
{
    public function load(Vcard $vcard): array
    {
        $dataPath = $vcard->data_path;
        if ($dataPath && Storage::disk('public')->exists($dataPath)) {
            $raw = Storage::disk('public')->get($dataPath);
            $data = json_decode($raw, true);
            if (is_array($data)) {
                return $data;
            }
        }

        $storageRoot = config('vcard.storage_root');
        $fallbackPath = $storageRoot . '/' . $vcard->subdomain . '/template/default.json';
        if (Storage::disk('public')->exists($fallbackPath)) {
            $raw = Storage::disk('public')->get($fallbackPath);
            $data = json_decode($raw, true);
            if (is_array($data)) {
                return $data;
            }
        }

        $templateRoot = config('vcard.template_root');
        $filePath = $templateRoot . DIRECTORY_SEPARATOR . $vcard->template_key . DIRECTORY_SEPARATOR . 'default.json';
        if ($templateRoot && File::exists($filePath)) {
            $raw = File::get($filePath);
            $data = json_decode($raw, true);
            if (is_array($data)) {
                return $data;
            }
        }

        return [];
    }

    public function save(Vcard $vcard, array $payload): void
    {
        $json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $storageRoot = config('vcard.storage_root');

        $dataPath = $vcard->data_path ?: $storageRoot . '/' . $vcard->subdomain . '/data.json';
        Storage::disk('public')->put($dataPath, $json);

        $templateDefault = $storageRoot . '/' . $vcard->subdomain . '/template/default.json';
        Storage::disk('public')->put($templateDefault, $json);

        $vcard->update([
            'data_path' => $dataPath,
        ]);
    }
}
