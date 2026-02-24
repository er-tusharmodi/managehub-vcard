<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VcardVisit extends Model
{
    protected $table = 'vcard_visits';
    
    protected $fillable = [
        'vcard_id',
        'ip_address',
        'user_agent',
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
