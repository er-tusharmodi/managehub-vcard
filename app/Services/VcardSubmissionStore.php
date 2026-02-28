<?php

namespace App\Services;

use App\Repositories\Contracts\SubmissionRepository;
use App\Models\Vcard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class VcardSubmissionStore
{
    public function __construct(private readonly SubmissionRepository $submissionRepository)
    {
    }

    public function store(string $type, Vcard $vcard, array $payload, array $fields = []): Model
    {
        $type = strtolower($type);

        $model = $this->submissionRepository->create($type, $vcard, $payload, $fields);

        if ((string) config('app.vcard_storage_mode', 'file_only') === 'file_only') {
            $this->appendJsonLog($type, $vcard, $payload, $model, $fields);
        }

        return $model;
    }

    private function appendJsonLog(string $type, Vcard $vcard, array $payload, Model $model, array $fields): void
    {
        $disk = Storage::disk('local');
        $directory = "vcard-submissions/{$vcard->subdomain}";
        $path = "{$directory}/{$type}.json";

        if (!$disk->exists($directory)) {
            $disk->makeDirectory($directory);
        }

        $entries = [];
        if ($disk->exists($path)) {
            $existing = json_decode($disk->get($path), true);
            if (is_array($existing)) {
                $entries = $existing;
            }
        }

        $entries[] = [
            'id' => $model->getKey(),
            'vcard_id' => $vcard->id,
            'subdomain' => $vcard->subdomain,
            'source_template' => $fields['source_template'] ?? $vcard->template_key,
            'name' => $fields['name'] ?? null,
            'email' => $fields['email'] ?? null,
            'phone' => $fields['phone'] ?? null,
            'message' => $fields['message'] ?? null,
            'items' => $fields['items'] ?? null,
            'total' => $fields['total'] ?? null,
            'payload' => $payload,
            'created_at' => now()->toIso8601String(),
        ];

        $disk->put($path, json_encode($entries, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
