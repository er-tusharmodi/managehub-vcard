<?php

namespace App\Repositories\DualWrite;

use App\Models\WebsitePage;
use App\Repositories\Contracts\WebsitePageRepository;
use App\Repositories\Mongo\MongoWebsitePageRepository;
use App\Repositories\Sql\SqlWebsitePageRepository;
use Illuminate\Support\Collection;

class DualWriteWebsitePageRepository implements WebsitePageRepository
{
    public function __construct(
        private readonly SqlWebsitePageRepository $sqlRepository,
        private readonly MongoWebsitePageRepository $mongoRepository,
    ) {
    }

    public function findBySlug(string $slug): ?WebsitePage
    {
        return $this->sqlRepository->findBySlug($slug);
    }

    public function firstOrCreateHome(): WebsitePage
    {
        return $this->sqlRepository->firstOrCreateHome();
    }

    public function allOrderedByTitle(): Collection
    {
        return $this->sqlRepository->allOrderedByTitle();
    }

    public function updateData(WebsitePage $page, array $data): void
    {
        $this->sqlRepository->updateData($page, $data);
        $this->mongoRepository->updateData($page, $data);
    }

    public function updateAttributes(WebsitePage $page, array $attributes): void
    {
        $this->sqlRepository->updateAttributes($page, $attributes);
        $this->mongoRepository->updateAttributes($page, $attributes);
    }
}
