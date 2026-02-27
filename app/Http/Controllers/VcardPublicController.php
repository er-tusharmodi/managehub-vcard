<?php

namespace App\Http\Controllers;

use App\Models\Vcard;
use App\Models\VcardVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;
use Symfony\Component\HttpFoundation\Response;

class VcardPublicController extends Controller
{
    public function show(Request $request, string $subdomain): Response
    {
        $vcard = Vcard::where('subdomain', $subdomain)->firstOrFail();

        if (!$vcard->isSubscriptionActive()) {
            if ($request->routeIs('vcard.public')) {
                return redirect('/inactive');
            }

            return redirect()->route('vcard.inactive', ['subdomain' => $subdomain]);
        }

        // Track the visit
        $this->trackVisit($vcard, $request);

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
        $content = $this->injectSubdomain($content, $subdomain);

        return response($content);
    }

    public function inactive(Request $request, string $subdomain): Response
    {
        $vcard = Vcard::where('subdomain', $subdomain)->firstOrFail();

        return response()->view('vcards.subscription-inactive', [
            'vcard' => $vcard,
        ], 403);
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

    private function injectSubdomain(string $content, string $subdomain): string
    {
        // Inject the subdomain variable correctly
        $search = 'window.__VCARD_SUBDOMAIN__ = "";';
        $replace = 'window.__VCARD_SUBDOMAIN__ = ' . json_encode($subdomain) . ';';
        
        return str_replace($search, $replace, $content);
    }

    private function trackVisit(Vcard $vcard, Request $request): void
    {
        // Get client IP address
        $ipAddress = $request->getClientIp();
        $userAgent = $request->header('User-Agent');
        $referrer = $request->headers->get('referer');
        $pageUrl = $request->fullUrl();

        $agent = new Agent();
        $agent->setUserAgent($userAgent ?? '');
        $device = 'Desktop';
        if ($agent->isTablet()) {
            $device = 'Tablet';
        } elseif ($agent->isMobile()) {
            $device = 'Mobile';
        }

        // Create visit record
        VcardVisit::create([
            'vcard_id' => $vcard->id,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'page_url' => $pageUrl,
            'referrer' => $referrer,
            'browser' => $agent->browser() ?: null,
            'device' => $device,
            'platform' => $agent->platform() ?: null,
            'country' => null,
            'visited_at' => now(),
        ]);
    }
}
