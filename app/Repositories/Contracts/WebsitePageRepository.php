<?php

namespace App\Repositories\Contracts;

use App\Models\WebsitePage;
use Illuminate\Support\Collection;

interface WebsitePageRepository
{
    public function findBySlug(string $slug): ?WebsitePage;

    public function firstOrCreateHome(): WebsitePage;

    public function allOrderedByTitle(): Collection;

    public function updateData(WebsitePage $page, array $data): void;

    public function updateAttributes(WebsitePage $page, array $attributes): void;
}
