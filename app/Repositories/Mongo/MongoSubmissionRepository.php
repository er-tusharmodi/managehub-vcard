<?php

namespace App\Repositories\Mongo;

use App\Models\Mongo\VcardSubmission;
use App\Models\Vcard;
use App\Repositories\Contracts\SubmissionRepository;
use Illuminate\Database\Eloquent\Model;

class MongoSubmissionRepository implements SubmissionRepository
{
    private const SUPPORTED_TYPES = ['order', 'booking', 'enquiry', 'contact'];

    public function create(string $type, Vcard $vcard, array $payload, array $fields = []): Model
    {
        $type = strtolower($type);

        if (!in_array($type, self::SUPPORTED_TYPES, true)) {
            throw new \InvalidArgumentException("Unsupported submission type: {$type}");
        }

        return VcardSubmission::create([
            'legacy_vcard_id' => $vcard->id,
            'subdomain' => $vcard->subdomain,
            'submission_type' => $type,
            'source_template' => $fields['source_template'] ?? $vcard->template_key,
            'name' => $fields['name'] ?? null,
            'email' => $fields['email'] ?? null,
            'phone' => $fields['phone'] ?? null,
            'message' => $fields['message'] ?? null,
            'items' => $fields['items'] ?? null,
            'total' => $fields['total'] ?? null,
            'payload' => $payload,
            'submitted_at' => now(),
        ]);
    }
}
