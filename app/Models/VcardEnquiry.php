<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VcardEnquiry extends Model
{
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
        'total' => 'decimal:2',
    ];

    public function vcard(): BelongsTo
    {
        return $this->belongsTo(Vcard::class);
    }
}
