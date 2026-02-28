<?php

namespace App\Repositories\Sql;

use App\Models\WebsiteSetting;
use App\Repositories\Contracts\WebsiteSettingRepository;

class SqlWebsiteSettingRepository implements WebsiteSettingRepository
{
    public function get(string $key, mixed $default = null): mixed
    {
        return WebsiteSetting::where('key', $key)->value('value') ?? $default;
    }

    public function getMany(array $keys): array
    {
        if (empty($keys)) {
            return [];
        }

        return WebsiteSetting::whereIn('key', $keys)
            ->pluck('value', 'key')
            ->toArray();
    }

    public function all(): array
    {
        return WebsiteSetting::pluck('value', 'key')->toArray();
    }

    public function set(string $key, mixed $value): void
    {
        WebsiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public function setMany(array $pairs): void
    {
        foreach ($pairs as $key => $value) {
            $this->set((string) $key, $value);
        }
    }
}
