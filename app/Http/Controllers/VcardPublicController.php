<?php

namespace App\Http\Controllers;

use App\Models\Vcard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class VcardPublicController extends Controller
{
    public function show(Request $request, string $subdomain): Response
    {
        $vcard = Vcard::where('subdomain', $subdomain)->firstOrFail();

        if (!$vcard->template_path) {
            abort(404);
        }

        $templateDir = Storage::disk('public')->path($vcard->template_path);
        $indexPath = $templateDir . DIRECTORY_SEPARATOR . 'index.php';

        if (!file_exists($indexPath)) {
            abort(404);
        }

        $content = $this->renderTemplate($indexPath);
        $baseHref = '/storage/' . trim($vcard->template_path, '/') . '/';

        $content = $this->injectBaseHref($content, $baseHref);

        return response($content);
    }

    private function renderTemplate(string $indexPath): string
    {
        $phpBinary = PHP_BINARY;
        $command = escapeshellarg($phpBinary) . ' ' . escapeshellarg($indexPath);
        $output = shell_exec($command);

        return is_string($output) ? $output : '';
    }

    private function injectBaseHref(string $content, string $baseHref): string
    {
        if (stripos($content, '<head>') === false) {
            return $content;
        }

        return str_ireplace('<head>', '<head><base href="' . $baseHref . '">', $content);
    }
}
