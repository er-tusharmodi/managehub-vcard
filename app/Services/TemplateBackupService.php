<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class TemplateBackupService
{
    protected $backupPath = 'template-backups';

    /**
     * Create a backup of the template
     */
    public function backup(string $templateKey): string
    {
        $timestamp = Carbon::now()->format('Y-m-d_His');
        $backupFolder = "{$this->backupPath}/{$templateKey}/{$timestamp}";
        
        $templatePath = base_path("vcard-template/{$templateKey}");
        $backupFullPath = storage_path("app/{$backupFolder}");

        if (!File::exists($templatePath)) {
            throw new \Exception("Template {$templateKey} not found");
        }

        // Create backup directory
        File::makeDirectory($backupFullPath, 0755, true, true);

        // Copy all template files
        File::copyDirectory($templatePath, $backupFullPath);

        return $backupFolder;
    }

    /**
     * Restore a template from backup
     */
    public function restore(string $templateKey, string $timestamp): bool
    {
        $backupFolder = "{$this->backupPath}/{$templateKey}/{$timestamp}";
        $backupFullPath = storage_path("app/{$backupFolder}");
        $templatePath = base_path("vcard-template/{$templateKey}");

        if (!File::exists($backupFullPath)) {
            throw new \Exception("Backup not found");
        }

        // Remove current template
        if (File::exists($templatePath)) {
            File::deleteDirectory($templatePath);
        }

        // Restore from backup
        File::copyDirectory($backupFullPath, $templatePath);

        return true;
    }

    /**
     * List all backups for a template
     */
    public function listBackups(string $templateKey): array
    {
        $backupFolder = "{$this->backupPath}/{$templateKey}";
        $backupFullPath = storage_path("app/{$backupFolder}");

        if (!File::exists($backupFullPath)) {
            return [];
        }

        $backups = [];
        $directories = File::directories($backupFullPath);

        foreach ($directories as $dir) {
            $timestamp = basename($dir);
            $backups[] = [
                'timestamp' => $timestamp,
                'date' => Carbon::createFromFormat('Y-m-d_His', $timestamp),
                'path' => $dir,
                'size' => $this->getDirectorySize($dir),
            ];
        }

        // Sort by date descending (newest first)
        usort($backups, function ($a, $b) {
            return $b['date']->timestamp <=> $a['date']->timestamp;
        });

        return $backups;
    }

    /**
     * Delete a specific backup
     */
    public function deleteBackup(string $templateKey, string $timestamp): bool
    {
        $backupFolder = "{$this->backupPath}/{$templateKey}/{$timestamp}";
        $backupFullPath = storage_path("app/{$backupFolder}");

        if (File::exists($backupFullPath)) {
            File::deleteDirectory($backupFullPath);
            return true;
        }

        return false;
    }

    /**
     * Delete all backups for a template
     */
    public function deleteAllBackups(string $templateKey): bool
    {
        $backupFolder = "{$this->backupPath}/{$templateKey}";
        $backupFullPath = storage_path("app/{$backupFolder}");

        if (File::exists($backupFullPath)) {
            File::deleteDirectory($backupFullPath);
            return true;
        }

        return false;
    }

    /**
     * Get directory size in bytes
     */
    protected function getDirectorySize(string $path): int
    {
        $size = 0;
        $files = File::allFiles($path);

        foreach ($files as $file) {
            $size += $file->getSize();
        }

        return $size;
    }

    /**
     * Format bytes to human readable size
     */
    public function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
