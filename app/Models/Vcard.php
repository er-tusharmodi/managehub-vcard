<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

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
        'subscription_status',
        'subscription_started_at',
        'subscription_expires_at',
        'created_by',
    ];

    protected $casts = [
        'domain_verified_at' => 'datetime',
        'subscription_started_at' => 'datetime',
        'subscription_expires_at' => 'datetime',
    ];

    public function isSubscriptionActive(): bool
    {
        if ($this->subscription_status !== 'active') {
            return false;
        }

        if ($this->subscription_started_at && now()->lt($this->subscription_started_at)) {
            return false;
        }

        if ($this->subscription_expires_at && now()->gt($this->subscription_expires_at)) {
            return false;
        }

        return true;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function visits(): HasMany
    {
        return $this->hasMany(VcardVisit::class);
    }

    public function getTotalVisitors(): int
    {
        return $this->visits()->count();
    }

    public function getTodayVisitors(): int
    {
        return $this->visits()
            ->whereDate('visited_at', today())
            ->count();
    }

    public function getThisWeekVisitors(): int
    {
        return $this->visits()
            ->whereBetween('visited_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->count();
    }

    public function getThisMonthVisitors(): int
    {
        return $this->visits()
            ->whereMonth('visited_at', now()->month)
            ->whereYear('visited_at', now()->year)
            ->count();
    }

    public function getRecentVisitors(int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        return $this->visits()
            ->latest('visited_at')
            ->limit($limit)
            ->get();
    }
}

