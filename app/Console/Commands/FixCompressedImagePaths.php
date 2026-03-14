<?php

namespace App\Console\Commands;

use App\Models\Vcard;
use App\Models\WebsitePage;
use Illuminate\Console\Command;

class FixCompressedImagePaths extends Command
{
    protected $signature = 'images:fix-paths {--dry-run : Show what would change without saving}';
    protected $description = 'Fix stored image paths after compression renamed files to .jpg';

    private const AFFECTED_PREFIXES = ['/storage/branding/', '/template-assets/'];

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        if ($dryRun) { $this->warn('[DRY RUN] No changes will be saved.'); }

        $total = 0;
        $total += $this->fixWebsitePages($dryRun);
        $total += $this->fixTemplateDefaultJsons($dryRun);
        $total += $this->fixVcardDataContent($dryRun);

        $this->newLine();
        $this->info("Total updated: {$total}");
        if ($dryRun) { $this->warn('Run without --dry-run to apply.'); }
        return self::SUCCESS;
    }

    private function fixWebsitePages(bool $dryRun): int
    {
        $this->line('');
        $this->line('<fg=cyan>1. WebsitePages (branding URLs)</>');
        $fixed = 0;

        foreach (WebsitePage::all() as $page) {
            $data     = $page->data ?? [];
            $branding = $data['branding'] ?? null;
            if (!is_array($branding)) { continue; }

            $changed = false;
            foreach (['logo_url', 'favicon_url', 'footer_logo_url'] as $key) {
                if (empty($branding[$key])) { continue; }
                $new = $this->fixPath($branding[$key]);
                if ($new !== $branding[$key]) {
                    $this->line("  [{$page->slug}] {$key}: {$branding[$key]} → {$new}");
                    $branding[$key] = $new;
                    $changed = true;
                }
            }

            if ($changed) {
                $fixed++;
                if (!$dryRun) {
                    $data['branding'] = $branding;
                    $page->data = $data;
                    $page->save();
                }
            }
        }

        $this->line("  Fixed: {$fixed} page(s)");
        return $fixed;
    }

    private function fixTemplateDefaultJsons(bool $dryRun): int
    {
        $this->line('');
        $this->line('<fg=cyan>2. Template default.json files</>');
        $fixed = 0;

        foreach (glob(public_path('template-assets/*/default.json')) ?: [] as $file) {
            $original = file_get_contents($file);
            $updated  = $this->fixJsonString($original);
            if ($updated !== $original) {
                $fixed++;
                $this->line('  Fixed: ' . basename(dirname($file)) . '/default.json');
                if (!$dryRun) { file_put_contents($file, $updated); }
            }
        }

        $this->line("  Fixed: {$fixed} file(s)");
        return $fixed;
    }

    private function fixVcardDataContent(bool $dryRun): int
    {
        $this->line('');
        $this->line('<fg=cyan>3. Vcard data_content (MongoDB)</>');
        $fixed = 0;

        foreach (Vcard::all() as $vcard) {
            $content = $vcard->data_content;
            if (empty($content) || !is_array($content)) { continue; }

            $original = json_encode($content, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $updated  = $this->fixJsonString($original);

            if ($updated !== $original) {
                $fixed++;
                $this->line("  Fixed vcard: {$vcard->subdomain}");
                if (!$dryRun) {
                    $vcard->update(['data_content' => json_decode($updated, true)]);
                }
            }
        }

        $this->line("  Fixed: {$fixed} vcard(s)");
        return $fixed;
    }

    private function fixPath(string $path): string
    {
        foreach (self::AFFECTED_PREFIXES as $prefix) {
            if (str_contains($path, $prefix)) {
                return preg_replace('/\.(png|webp|gif|jpeg)$/i', '.jpg', $path);
            }
        }
        return $path;
    }

    private function fixJsonString(string $json): string
    {
        foreach (self::AFFECTED_PREFIXES as $prefix) {
            $json = preg_replace_callback(
                '/' . preg_quote($prefix, '/') . '[^"\'\\\\]+\.(png|webp|gif|jpeg)(?=["\'])/i',
                fn ($m) => preg_replace('/\.(png|webp|gif|jpeg)$/i', '.jpg', $m[0]),
                $json
            );
        }
        return $json;
    }
}
