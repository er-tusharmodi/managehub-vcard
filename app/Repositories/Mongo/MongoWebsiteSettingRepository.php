<?php

namespace App\Repositories\Mongo;

use App\Models\Mongo\WebsiteSetting as MongoWebsiteSetting;
use App\Repositories\Contracts\WebsiteSettingRepository;
use App\Repositories\Sql\SqlWebsiteSettingRepository;

class MongoWebsiteSettingRepository implements WebsiteSettingRepository
{
    public function __construct(private readonly SqlWebsiteSettingRepository $fallbackRepository)
    {
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $value = MongoWebsiteSetting::query()->where('key', $key)->value('value');

        if ($value !== null) {
            return $value;
        }

        return $this->fallbackRepository->get($key, $default);
    }

    public function getMany(array $keys): array
    {
        if (empty($keys)) {
            return [];
        }

        $mongoValues = MongoWebsiteSetting::query()
            ->whereIn('key', $keys)
            ->pluck('value', 'key')
            ->toArray();

        if (count($mongoValues) === count($keys)) {
            return $mongoValues;
        }

        return array_merge(
            $this->fallbackRepository->getMany($keys),
            $mongoValues
        );
    }

    public function all(): array
    {
        $mongoValues = MongoWebsiteSetting::query()->pluck('value', 'key')->toArray();

        if (!empty($mongoValues)) {
            return $mongoValues;
        }

        return $this->fallbackRepository->all();
    }

    public function set(string $key, mixed $value): void
    {
        MongoWebsiteSetting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public function setMany(array $pairs): void
    {
        foreach ($pairs as $key => $value) {
            $this->set((string) $key, $value);
        }
    }
}
