<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VcardVisit extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'vcard_visits';
    
    protected $fillable = [
        'vcard_id',
        'ip_address',
        'user_agent',
        'page_url',
        'referrer',
        'browser',
        'device',
        'platform',
        'country',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    public function vcard(): BelongsTo
    {
        return $this->belongsTo(Vcard::class);
    }
}
