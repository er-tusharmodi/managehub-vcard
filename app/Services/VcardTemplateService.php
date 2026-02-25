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

        $templates = [];

        foreach (File::directories($root) as $dir) {
            $key = basename($dir);
            $defaultPath = $dir . DIRECTORY_SEPARATOR . 'default.json';
            $title = $key; // Use folder name directly

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
     * Delete a template folder
     */
    public function deleteTemplate(string $templateKey): bool
    {
        $path = $this->templatePath($templateKey);
        
        if (!$path || !File::isDirectory($path)) {
            return false;
        }

        if (!$this->canDelete($templateKey)) {
            throw new \Exception("Cannot delete template. vCards are using this template.");
        }

        File::deleteDirectory($path);
        return true;
    }

    /**
     * Get all template file contents
     */
    public function getTemplateFiles(string $templateKey): array
    {
        $path = $this->templatePath($templateKey);
        
        if (!$path) {
            throw new \Exception("Template not found");
        }

        $files = [
            'php' => '',
            'css' => '',
            'js' => '',
            'json' => '',
        ];

        $phpPath = $path . DIRECTORY_SEPARATOR . 'index.php';
        if (File::exists($phpPath)) {
            $files['php'] = File::get($phpPath);
        }

        $cssPath = $path . DIRECTORY_SEPARATOR . 'style.css';
        if (File::exists($cssPath)) {
            $files['css'] = File::get($cssPath);
        }

        $jsPath = $path . DIRECTORY_SEPARATOR . 'script.js';
        if (File::exists($jsPath)) {
            $files['js'] = File::get($jsPath);
        }

        $jsonPath = $path . DIRECTORY_SEPARATOR . 'default.json';
        if (File::exists($jsonPath)) {
            $files['json'] = File::get($jsonPath);
        }

        return $files;
    }

    /**
     * Update template files
     */
    public function updateTemplateFiles(string $templateKey, array $files): bool
    {
        $path = $this->templatePath($templateKey);
        
        if (!$path) {
            throw new \Exception("Template not found");
        }

        if (isset($files['php'])) {
            $phpPath = $path . DIRECTORY_SEPARATOR . 'index.php';
            File::put($phpPath, $files['php']);
        }

        if (isset($files['css'])) {
            $cssPath = $path . DIRECTORY_SEPARATOR . 'style.css';
            File::put($cssPath, $files['css']);
        }

        if (isset($files['js'])) {
            $jsPath = $path . DIRECTORY_SEPARATOR . 'script.js';
            File::put($jsPath, $files['js']);
        }

        if (isset($files['json'])) {
            $jsonPath = $path . DIRECTORY_SEPARATOR . 'default.json';
            File::put($jsonPath, $files['json']);
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
