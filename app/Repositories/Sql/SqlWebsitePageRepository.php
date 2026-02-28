<?php

namespace App\Repositories\Sql;

use App\Models\WebsitePage;
use App\Repositories\Contracts\WebsitePageRepository;
use Illuminate\Support\Collection;

class SqlWebsitePageRepository implements WebsitePageRepository
{
    public function findBySlug(string $slug): ?WebsitePage
    {
        return WebsitePage::query()->where('slug', $slug)->first();
    }

    public function firstOrCreateHome(): WebsitePage
    {
        $page = $this->findBySlug('home');

        if ($page) {
            return $page;
        }

        return WebsitePage::create([
            'slug' => 'home',
            'title' => 'Home',
        ]);
    }

    public function allOrderedByTitle(): Collection
    {
        return WebsitePage::query()->orderBy('title')->get();
    }

    public function updateData(WebsitePage $page, array $data): void
    {
        $page->update(['data' => $data]);
    }

    public function updateAttributes(WebsitePage $page, array $attributes): void
    {
        $page->update($attributes);
    }
}
