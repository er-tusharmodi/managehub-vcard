<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vcard extends Model
{
    protected $fillable = [
        'user_id',
        'subdomain',
        'template_key',
        'client_name',
        'client_email',
        'client_phone',
        'client_address',
        'data_path',
        'template_path',
        'status',
        'created_by',
    ];

    protected $casts = [
        'domain_verified_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
