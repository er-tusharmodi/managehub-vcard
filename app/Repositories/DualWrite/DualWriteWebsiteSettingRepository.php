<?php

namespace App\Repositories\DualWrite;

use App\Repositories\Contracts\WebsiteSettingRepository;
use App\Repositories\Mongo\MongoWebsiteSettingRepository;
use App\Repositories\Sql\SqlWebsiteSettingRepository;

class DualWriteWebsiteSettingRepository implements WebsiteSettingRepository
{
    public function __construct(
        private readonly SqlWebsiteSettingRepository $sqlRepository,
        private readonly MongoWebsiteSettingRepository $mongoRepository,
    ) {
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->sqlRepository->get($key, $default);
    }

    public function getMany(array $keys): array
    {
        return $this->sqlRepository->getMany($keys);
    }

    public function all(): array
    {
        return $this->sqlRepository->all();
    }

    public function set(string $key, mixed $value): void
    {
        $this->sqlRepository->set($key, $value);
        $this->mongoRepository->set($key, $value);
    }

    public function setMany(array $pairs): void
    {
        $this->sqlRepository->setMany($pairs);
        $this->mongoRepository->setMany($pairs);
    }
}
