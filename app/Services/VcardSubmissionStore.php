<?php

namespace App\Services;

use App\Models\Vcard;
use App\Models\VcardBooking;
use App\Models\VcardContact;
use App\Models\VcardEnquiry;
use App\Models\VcardOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class VcardSubmissionStore
{
    private const TYPE_MODEL_MAP = [
        'order' => VcardOrder::class,
        'booking' => VcardBooking::class,
        'enquiry' => VcardEnquiry::class,
        'contact' => VcardContact::class,
    ];

    public function store(string $type, Vcard $vcard, array $payload, array $fields = []): Model
    {
        $type = strtolower($type);

        if (!array_key_exists($type, self::TYPE_MODEL_MAP)) {
            throw new \InvalidArgumentException("Unsupported submission type: {$type}");
        }

        $modelClass = self::TYPE_MODEL_MAP[$type];

        /** @var Model $model */
        $model = $modelClass::create([
            'vcard_id' => $vcard->id,
            'source_template' => $fields['source_template'] ?? $vcard->template_key,
            'name' => $fields['name'] ?? null,
            'email' => $fields['email'] ?? null,
            'phone' => $fields['phone'] ?? null,
            'message' => $fields['message'] ?? null,
            'items' => $fields['items'] ?? null,
            'total' => $fields['total'] ?? null,
            'payload' => $payload,
        ]);

        $this->appendJsonLog($type, $vcard, $payload, $model, $fields);

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
