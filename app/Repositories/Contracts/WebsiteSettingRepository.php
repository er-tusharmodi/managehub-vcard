<?php

namespace App\Repositories\Contracts;

interface WebsiteSettingRepository
{
    public function get(string $key, mixed $default = null): mixed;

    public function getMany(array $keys): array;

    public function all(): array;

    public function set(string $key, mixed $value): void;

    public function setMany(array $pairs): void;
}
