<?php

namespace App\Services;

use App\Models\VcardVisit;
use Illuminate\Support\Collection;

class VisitorAnalyticsService
{
    public function topPages(int $limit = 7): array
    {
        return VcardVisit::query()
            ->whereNotNull('page_url')
            ->get(['page_url'])
            ->groupBy('page_url')
            ->map(fn($group) => [
                'page' => $group->first()->page_url,
                'visits' => $group->count(),
            ])
            ->sortByDesc('visits')
            ->take($limit)
            ->values()
            ->toArray();
    }

    public function trafficSources(int $limit = 7): array
    {
        $rawSources = VcardVisit::query()
            ->get(['referrer']);

        $bucketed = [];
        foreach ($rawSources as $row) {
            $source = $this->normalizeSource($row->referrer);
            $bucketed[$source] = ($bucketed[$source] ?? 0) + 1;
        }

        return collect($bucketed)
            ->sortDesc()
            ->take($limit)
            ->map(fn ($visits, $source) => [
                'source' => $source,
                'visits' => $visits,
            ])
            ->values()
            ->toArray();
    }

    public function deviceBreakdown(): array
    {
        return VcardVisit::query()
            ->whereNotNull('device')
            ->get(['device'])
            ->groupBy('device')
            ->map(fn($group) => [
                'label' => $group->first()->device,
                'visits' => $group->count(),
            ])
            ->sortByDesc('visits')
            ->values()
            ->toArray();
    }

    public function browserBreakdown(): array
    {
        return VcardVisit::query()
            ->whereNotNull('browser')
            ->get(['browser'])
            ->groupBy('browser')
            ->map(fn($group) => [
                'label' => $group->first()->browser,
                'visits' => $group->count(),
            ])
            ->sortByDesc('visits')
            ->take(7)
            ->values()
            ->toArray();
    }

    private function normalizeSource(?string $referrer): string
    {
        if (!$referrer) {
            return 'Direct';
        }

        $host = parse_url($referrer, PHP_URL_HOST);
        if (!$host) {
            return 'Other';
        }

        $host = strtolower($host);
        if (str_contains($host, 'google.')) {
            return 'Google';
        }
        if (str_contains($host, 'facebook.')) {
            return 'Facebook';
        }
        if (str_contains($host, 'instagram.')) {
            return 'Instagram';
        }
        if (str_contains($host, 'twitter.') || str_contains($host, 'x.com')) {
            return 'Twitter/X';
        }
        if (str_contains($host, 'linkedin.')) {
            return 'LinkedIn';
        }
        if (str_contains($host, 'youtube.')) {
            return 'YouTube';
        }

        return ucfirst(str_replace('www.', '', $host));
    }
}
