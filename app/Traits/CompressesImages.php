<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait CompressesImages
{
    /**
     * Compress and store an uploaded image to Storage::disk('public').
     * Max 1200px on longest side, JPEG quality 75. Falls back to direct store() on GD failure.
     * Returns relative storage path (e.g. "vcards/xxx/uploads/img_xxx.jpg").
     */
    private function storeUploadedImage($file, string $directory): string
    {
        $realPath  = $file->getRealPath();
        $imageInfo = @getimagesize($realPath);

        if (!$imageInfo) {
            return $file->store($directory, 'public');
        }

        [$origWidth, $origHeight, $imageType] = $imageInfo;
        $maxDim = 1200;

        $src = match ($imageType) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($realPath),
            IMAGETYPE_PNG  => @imagecreatefrompng($realPath),
            IMAGETYPE_WEBP => @imagecreatefromwebp($realPath),
            IMAGETYPE_GIF  => @imagecreatefromgif($realPath),
            default        => null,
        };

        if (!$src) {
            return $file->store($directory, 'public');
        }

        $scale = min($maxDim / $origWidth, $maxDim / $origHeight, 1.0);
        $newW  = (int) round($origWidth  * $scale);
        $newH  = (int) round($origHeight * $scale);
        $dst   = imagecreatetruecolor($newW, $newH);

        // White background so transparent PNGs don't produce black fill when converted to JPEG
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefill($dst, 0, 0, $white);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origWidth, $origHeight);
        imagedestroy($src);

        $tmpFile = tempnam(sys_get_temp_dir(), 'vcimg_') . '.jpg';
        imagejpeg($dst, $tmpFile, 75);
        imagedestroy($dst);

        $storedPath = $directory . '/' . uniqid('img_', true) . '.jpg';
        Storage::disk('public')->put($storedPath, file_get_contents($tmpFile));
        @unlink($tmpFile);

        return $storedPath;
    }

    /**
     * Compress an uploaded image and save directly to a filesystem path (not Storage::disk).
     * Used when storing to the public/ directory (e.g. template assets).
     * Output is always JPEG quality 75. Falls back to File::copy on GD failure.
     */
    private function compressImageToPath($file, string $destPath): void
    {
        $realPath  = $file->getRealPath();
        $imageInfo = @getimagesize($realPath);

        if (!$imageInfo) {
            File::copy($realPath, $destPath);
            return;
        }

        [$origWidth, $origHeight, $imageType] = $imageInfo;
        $maxDim = 1200;

        $src = match ($imageType) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($realPath),
            IMAGETYPE_PNG  => @imagecreatefrompng($realPath),
            IMAGETYPE_WEBP => @imagecreatefromwebp($realPath),
            IMAGETYPE_GIF  => @imagecreatefromgif($realPath),
            default        => null,
        };

        if (!$src) {
            File::copy($realPath, $destPath);
            return;
        }

        $scale = min($maxDim / $origWidth, $maxDim / $origHeight, 1.0);
        $newW  = (int) round($origWidth  * $scale);
        $newH  = (int) round($origHeight * $scale);
        $dst   = imagecreatetruecolor($newW, $newH);

        // White background so transparent PNGs don't produce black fill when converted to JPEG
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefill($dst, 0, 0, $white);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origWidth, $origHeight);
        imagedestroy($src);

        imagejpeg($dst, $destPath, 75);
        imagedestroy($dst);
    }
}
