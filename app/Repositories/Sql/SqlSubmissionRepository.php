<?php

namespace App\Repositories\Sql;

use App\Models\Vcard;
use App\Models\VcardBooking;
use App\Models\VcardContact;
use App\Models\VcardEnquiry;
use App\Models\VcardOrder;
use App\Repositories\Contracts\SubmissionRepository;
use Illuminate\Database\Eloquent\Model;

class SqlSubmissionRepository implements SubmissionRepository
{
    private const TYPE_MODEL_MAP = [
        'order' => VcardOrder::class,
        'booking' => VcardBooking::class,
        'enquiry' => VcardEnquiry::class,
        'contact' => VcardContact::class,
    ];

    public function create(string $type, Vcard $vcard, array $payload, array $fields = []): Model
    {
        $type = strtolower($type);

        if (!array_key_exists($type, self::TYPE_MODEL_MAP)) {
            throw new \InvalidArgumentException("Unsupported submission type: {$type}");
        }

        $modelClass = self::TYPE_MODEL_MAP[$type];

        return $modelClass::create([
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
    }
}
