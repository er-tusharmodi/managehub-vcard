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
}
