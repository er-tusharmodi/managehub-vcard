<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CompressStorageImages extends Command
{
    protected $signature = 'images:compress
                            {--disk=public : Storage disk to scan}
                            {--dir=        : Sub-directory within the disk (default: all)}
                            {--max-dim=1200 : Maximum width/height in pixels}
                            {--quality=75  : JPEG output quality (1-100)}
                            {--dry-run     : Preview what would be compressed without changing files}';

    protected $description = 'Compress existing uploaded images in storage (in-place)';

    public function handle(): int
    {
        $disk    = $this->option('disk');
        $subDir  = $this->option('dir') ?: '';
        $maxDim  = (int) $this->option('max-dim');
        $quality = (int) $this->option('quality');
        $dryRun  = $this->option('dry-run');

        if (!extension_loaded('gd')) {
            $this->error('GD extension is not available. Cannot compress images.');
            return self::FAILURE;
        }

        $this->info('Scanning ' . ($subDir ?: 'all directories') . ' on disk [' . $disk . ']…');

        $allFiles = Storage::disk($disk)->allFiles($subDir ?: '/');

        $images = array_filter($allFiles, fn ($f) => preg_match('/\.(jpe?g|png|webp|gif)$/i', $f));
        $images = array_values($images);

        if (empty($images)) {
            $this->info('No images found.');
            return self::SUCCESS;
        }

        $this->info('Found ' . count($images) . ' image(s).');
        if ($dryRun) {
            $this->warn('[DRY RUN] No files will be changed.');
        }

        $bar         = $this->output->createProgressBar(count($images));
        $bar->start();

        $totalBefore = 0;
        $totalAfter  = 0;
        $skipped     = 0;
        $failed      = 0;
        $compressed  = 0;

        foreach ($images as $relativePath) {
            $bar->advance();

            $realPath = Storage::disk($disk)->path($relativePath);
            $sizeBefore = filesize($realPath);
            $totalBefore += $sizeBefore;

            $imageInfo = @getimagesize($realPath);
            if (!$imageInfo) {
                $skipped++;
                continue;
            }

            [$origWidth, $origHeight, $imageType] = $imageInfo;

            $src = match ($imageType) {
                IMAGETYPE_JPEG => @imagecreatefromjpeg($realPath),
                IMAGETYPE_PNG  => @imagecreatefrompng($realPath),
                IMAGETYPE_WEBP => @imagecreatefromwebp($realPath),
                IMAGETYPE_GIF  => @imagecreatefromgif($realPath),
                default        => null,
            };

            if (!$src) {
                $skipped++;
                $totalAfter += $sizeBefore;
                continue;
            }

            $scale = min($maxDim / $origWidth, $maxDim / $origHeight, 1.0);
            $newW  = (int) round($origWidth  * $scale);
            $newH  = (int) round($origHeight * $scale);
            $dst   = imagecreatetruecolor($newW, $newH);

            // White background so transparent PNGs don't go black
            $white = imagecolorallocate($dst, 255, 255, 255);
            imagefill($dst, 0, 0, $white);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origWidth, $origHeight);
            imagedestroy($src);

            $tmpFile = tempnam(sys_get_temp_dir(), 'vcimg_') . '.jpg';
            imagejpeg($dst, $tmpFile, $quality);
            imagedestroy($dst);

            $sizeAfter = filesize($tmpFile);
            $totalAfter += $sizeAfter;

            if (!$dryRun) {
                // Replace in-place — rename to .jpg if needed
                $newRelativePath = preg_replace('/\.(jpe?g|png|webp|gif)$/i', '.jpg', $relativePath);
                Storage::disk($disk)->put($newRelativePath, file_get_contents($tmpFile));

                // Delete original if extension changed
                if ($newRelativePath !== $relativePath) {
                    Storage::disk($disk)->delete($relativePath);
                }
            }

            @unlink($tmpFile);
            $compressed++;
        }

        $bar->finish();
        $this->newLine(2);

        $savedBytes = $totalBefore - $totalAfter;
        $savedPct   = $totalBefore > 0 ? round($savedBytes / $totalBefore * 100, 1) : 0;

        $this->table(
            ['Metric', 'Value'],
            [
                ['Images found',     count($images)],
                ['Compressed',       $compressed],
                ['Skipped (no GD)',  $skipped],
                ['Failed',           $failed],
                ['Size before',      $this->formatBytes($totalBefore)],
                ['Size after',       $this->formatBytes($totalAfter)],
                ['Saved',            $this->formatBytes($savedBytes) . ' (' . $savedPct . '%)'],
            ]
        );

        if ($dryRun) {
            $this->warn('DRY RUN complete — no files were changed. Run without --dry-run to apply.');
        } else {
            $this->info('Done.');
        }

        return self::SUCCESS;
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024, 1) . ' KB';
        }
        return $bytes . ' B';
    }
}
