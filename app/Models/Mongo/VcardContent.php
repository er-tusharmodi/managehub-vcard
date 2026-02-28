<?php

namespace App\Models\Mongo;

use MongoDB\Laravel\Eloquent\Model;

class VcardContent extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'vcards';

    protected $fillable = [
        'legacy_vcard_id',
        'user_id',
        'subdomain',
        'template_key',
        'client_name',
        'client_email',
        'client_phone',
        'client_address',
        'qr_code_path',
        'status',
        'subscription_status',
        'subscription_started_at',
        'subscription_expires_at',
        'domain_verified_at',
        'created_by',
        'data_content',
    ];

    protected $casts = [
        'data_content' => 'array',
        'subscription_started_at' => 'datetime',
        'subscription_expires_at' => 'datetime',
        'domain_verified_at' => 'datetime',
    ];
}
