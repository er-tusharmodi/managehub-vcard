<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VcardContact extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'vcard_contacts';

    protected $fillable = [
        'vcard_id',
        'source_template',
        'name',
        'email',
        'phone',
        'message',
        'items',
        'total',
        'payload',
    ];

    protected $casts = [
        'items' => 'array',
        'payload' => 'array',
    ];

    public function getTotalAttribute(mixed $value): ?float
    {
        if ($value instanceof \MongoDB\BSON\Decimal128) {
            return (float)(string)$value;
        }
        return $value === null ? null : (float)$value;
    }

    public function vcard(): BelongsTo
    {
        return $this->belongsTo(Vcard::class);
    }
}
