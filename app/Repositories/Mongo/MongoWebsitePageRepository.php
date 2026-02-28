<?php

namespace App\Repositories\Mongo;

use App\Models\Mongo\WebsitePage as MongoWebsitePage;
use App\Models\WebsitePage;
use App\Repositories\Contracts\WebsitePageRepository;
use App\Repositories\Sql\SqlWebsitePageRepository;
use Illuminate\Support\Collection;

class MongoWebsitePageRepository implements WebsitePageRepository
{
    public function __construct(private readonly SqlWebsitePageRepository $fallbackRepository)
    {
    }

    public function findBySlug(string $slug): ?WebsitePage
    {
        $document = MongoWebsitePage::query()->where('slug', $slug)->first();

        if ($document) {
            return $this->toWebsitePageModel($document->toArray());
        }

        return $this->fallbackRepository->findBySlug($slug);
    }

    public function firstOrCreateHome(): WebsitePage
    {
        $page = $this->findBySlug('home');

        if ($page) {
            return $page;
        }

        $fallback = $this->fallbackRepository->firstOrCreateHome();
        $this->upsert($fallback, []);

        return $fallback;
    }

    public function allOrderedByTitle(): Collection
    {
        $documents = MongoWebsitePage::query()->orderBy('title')->get();

        if ($documents->isNotEmpty()) {
            return $documents->map(fn ($document) => $this->toWebsitePageModel($document->toArray()));
        }

        return $this->fallbackRepository->allOrderedByTitle();
    }

    public function updateData(WebsitePage $page, array $data): void
    {
        $this->upsert($page, ['data' => $data]);
    }

    public function updateAttributes(WebsitePage $page, array $attributes): void
    {
        $this->upsert($page, $attributes);
    }

    private function upsert(WebsitePage $page, array $attributes): void
    {
        MongoWebsitePage::query()->updateOrCreate(
            ['slug' => $page->slug],
            array_merge($this->baseAttributes($page), $attributes)
        );
    }

    private function baseAttributes(WebsitePage $page): array
    {
        return [
            'slug' => $page->slug,
            'title' => $page->title,
            'meta_title' => $page->meta_title,
            'meta_description' => $page->meta_description,
            'hero_title' => $page->hero_title,
            'hero_title_highlight' => $page->hero_title_highlight,
            'hero_subtitle' => $page->hero_subtitle,
            'header_cta' => $page->header_cta,
            'hero_buttons' => $page->hero_buttons,
            'hero_image_path' => $page->hero_image_path,
            'categories' => $page->categories,
            'vcard_preview' => $page->vcard_preview,
            'how_it_works' => $page->how_it_works,
            'cta_section' => $page->cta_section,
            'about_title' => $page->about_title,
            'about_body' => $page->about_body,
            'about_image_path' => $page->about_image_path,
            'services' => $page->services,
            'testimonials' => $page->testimonials,
            'faqs' => $page->faqs,
            'footer_text' => $page->footer_text,
            'footer_about' => $page->footer_about,
            'footer_links' => $page->footer_links,
            'data' => $page->data,
        ];
    }

    private function toWebsitePageModel(array $payload): WebsitePage
    {
        unset($payload['_id']);

        $page = new WebsitePage();
        $page->fill($payload);
        $page->exists = true;

        return $page;
    }
}
