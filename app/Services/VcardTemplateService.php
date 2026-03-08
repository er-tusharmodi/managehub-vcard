<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class VcardTemplateService
{
    public function listTemplates(): array
    {
        $root = config('vcard.template_root');
        if (!$root || !File::isDirectory($root)) {
            return [];
        }

        // Pre-fetch all display_names from DB for efficiency
        $dbNames = \App\Models\Template::pluck('display_name', 'template_key');

        $templates = [];

        foreach (File::directories($root) as $dir) {
            $key = basename($dir);
            $defaultPath = $dir . DIRECTORY_SEPARATOR . 'default.json';
            $title = $dbNames->get($key) ?: $key; // Use DB display_name, fallback to folder key

            $templates[] = [
                'key' => $key,
                'title' => $title,
                'default_path' => $defaultPath,
            ];
        }

        return $templates;
    }

    public function loadDefaultJson(string $templateKey): array
    {
        $path = $this->defaultJsonPath($templateKey);
        if (!$path || !File::exists($path)) {
            return [];
        }

        $raw = File::get($path);
        $data = json_decode($raw, true);

        return is_array($data) ? $data : [];
    }

    public function templatePath(string $templateKey): ?string
    {
        $root = config('vcard.template_root');
        $dir = $root ? $root . DIRECTORY_SEPARATOR . $templateKey : null;

        if (!$dir || !File::isDirectory($dir)) {
            return null;
        }

        return $dir;
    }

    public function defaultJsonPath(string $templateKey): ?string
    {
        $dir = $this->templatePath($templateKey);
        if (!$dir) {
            return null;
        }

        return $dir . DIRECTORY_SEPARATOR . 'default.json';
    }

    /**
     * Check if a template can be deleted (no vCards use it)
     */
    public function canDelete(string $templateKey): bool
    {
        return \App\Models\Vcard::where('template_key', $templateKey)->doesntExist();
    }

    /**
     * Delete a template folder and its database record
     */
    public function deleteTemplate(string $templateKey): bool
    {
        $path = $this->templatePath($templateKey);

        if (!$this->canDelete($templateKey)) {
            throw new \Exception("Cannot delete template. vCards are using this template.");
        }

        // Delete filesystem directory if it exists
        if ($path && File::isDirectory($path)) {
            File::deleteDirectory($path);
        }

        // Delete database record so it no longer appears on the frontend
        \App\Models\Template::where('template_key', $templateKey)->delete();

        return true;
    }

    /**
     * Get actual file paths for a template's editable files.
     * - Blade view  : resources/views/vcards/templates/{key}.blade.php
     * - CSS         : public/vcard-assets/{key}/style.css
     * - JS          : public/vcard-assets/{key}/script.js
     * - JSON        : vcard-template/{key}/default.json
     */
    private function resolveFilePaths(string $templateKey): array
    {
        return [
            'php'  => resource_path('views/vcards/templates/' . $templateKey . '.blade.php'),
            'css'  => public_path('vcard-assets/' . $templateKey . '/style.css'),
            'js'   => public_path('vcard-assets/' . $templateKey . '/script.js'),
            'json' => $this->defaultJsonPath($templateKey),
        ];
    }

    /**
     * Get all template file contents
     */
    public function getTemplateFiles(string $templateKey): array
    {
        if (!$this->templatePath($templateKey)) {
            throw new \Exception("Template not found");
        }

        $paths = $this->resolveFilePaths($templateKey);

        $files = ['php' => '', 'css' => '', 'js' => '', 'json' => ''];

        foreach ($files as $key => $_) {
            if ($paths[$key] && File::exists($paths[$key])) {
                $files[$key] = File::get($paths[$key]);
            }
        }

        return $files;
    }

    /**
     * Update template files
     */
    public function updateTemplateFiles(string $templateKey, array $files): bool
    {
        if (!$this->templatePath($templateKey)) {
            throw new \Exception("Template not found");
        }

        $paths = $this->resolveFilePaths($templateKey);

        foreach (['php', 'css', 'js', 'json'] as $key) {
            if (isset($files[$key]) && $paths[$key]) {
                File::ensureDirectoryExists(dirname($paths[$key]));
                File::put($paths[$key], $files[$key]);
            }
        }

        return true;
    }

    /**
     * Get template default JSON data
     */
    public function getTemplateDefaultJson(string $templateKey): array
    {
        return $this->loadDefaultJson($templateKey);
    }

    /**
     * Update template default JSON data
     */
    public function updateTemplateDefaultJson(string $templateKey, array $data): bool
    {
        $jsonPath = $this->defaultJsonPath($templateKey);
        
        if (!$jsonPath) {
            throw new \Exception("Template not found");
        }

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        File::put($jsonPath, $json);

        return true;
    }

    /**
     * Get vCard count using a template
     */
    public function getVcardCount(string $templateKey): int
    {
        return \App\Models\Vcard::where('template_key', $templateKey)->count();
    }
}
