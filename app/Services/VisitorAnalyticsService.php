<?php

namespace App\Services;

use App\Models\VcardVisit;
use Illuminate\Support\Collection;

class VisitorAnalyticsService
{
    public function topPages(int $limit = 7): array
    {
        return VcardVisit::query()
            ->selectRaw('page_url, count(*) as visits')
            ->whereNotNull('page_url')
            ->groupBy('page_url')
            ->orderByDesc('visits')
            ->limit($limit)
            ->get()
            ->map(fn ($row) => [
                'page' => $row->page_url,
                'visits' => (int) $row->visits,
            ])
            ->toArray();
    }

    public function trafficSources(int $limit = 7): array
    {
        $rawSources = VcardVisit::query()
            ->selectRaw('referrer, count(*) as visits')
            ->groupBy('referrer')
            ->orderByDesc('visits')
            ->limit(100)
            ->get();

        $bucketed = [];
        foreach ($rawSources as $row) {
            $source = $this->normalizeSource($row->referrer);
            $bucketed[$source] = ($bucketed[$source] ?? 0) + (int) $row->visits;
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
            ->selectRaw('device, count(*) as visits')
            ->whereNotNull('device')
            ->groupBy('device')
            ->orderByDesc('visits')
            ->get()
            ->map(fn ($row) => [
                'label' => $row->device,
                'visits' => (int) $row->visits,
            ])
            ->toArray();
    }

    public function browserBreakdown(): array
    {
        return VcardVisit::query()
            ->selectRaw('browser, count(*) as visits')
            ->whereNotNull('browser')
            ->groupBy('browser')
            ->orderByDesc('visits')
            ->limit(7)
            ->get()
            ->map(fn ($row) => [
                'label' => $row->browser,
                'visits' => (int) $row->visits,
            ])
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
