<?php

namespace App\Models\Mongo;

use MongoDB\Laravel\Eloquent\Model;

class VcardSubmission extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'vcard_submissions';

    protected $fillable = [
        'legacy_vcard_id',
        'subdomain',
        'submission_type',
        'source_template',
        'name',
        'email',
        'phone',
        'message',
        'items',
        'total',
        'payload',
        'submitted_at',
        'status',
    ];

    protected $casts = [
        'items' => 'array',
        'payload' => 'array',
        'submitted_at' => 'datetime',
    ];

    public function getTotalAttribute(mixed $value): ?float
    {
        if ($value instanceof \MongoDB\BSON\Decimal128) {
            return (float)(string)$value;
        }
        return $value === null ? null : (float)$value;
    }
}
