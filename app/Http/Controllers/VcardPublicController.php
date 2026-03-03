<?php

namespace App\Http\Controllers;

use App\Models\Vcard;
use App\Models\VcardVisit;
use App\Repositories\Contracts\VcardContentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;
use Symfony\Component\HttpFoundation\Response;

class VcardPublicController extends Controller
{
    public function __construct(private readonly VcardContentRepository $contentRepository)
    {
    }

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

        // Load vCard content data from database (MongoDB via VcardContentRepository)
        $data = $this->contentRepository->load($vcard);

        // Build the asset base URL pointing to template files in storage
        // NOTE: url() strips trailing slashes, so we append '/' explicitly after calling url()
        $assetBase = $vcard->template_path
            ? url('storage/' . trim($vcard->template_path, '/')) . '/'
            : '';

        $templateView = 'vcards.templates.' . $vcard->template_key;

        // Use Blade template if it exists, otherwise fall back to legacy shell_exec render
        if (view()->exists($templateView)) {
            return response()->view($templateView, compact('data', 'vcard', 'subdomain', 'assetBase'));
        }

        return $this->legacyShellRender($vcard, $subdomain);
    }

    public function inactive(Request $request, string $subdomain): Response
    {
        $vcard = Vcard::where('subdomain', $subdomain)->firstOrFail();

        return response()->view('vcards.subscription-inactive', [
            'vcard' => $vcard,
        ], 403);
    }

    /**
     * Legacy render via shell_exec (used when no Blade template exists yet).
     */
    private function legacyShellRender(Vcard $vcard, string $subdomain): Response
    {
        if (!$vcard->template_path) {
            abort(404);
        }

        $templateDir = Storage::disk('public')->path($vcard->template_path);
        $indexPath = $templateDir . DIRECTORY_SEPARATOR . 'index.php';

        if (!file_exists($indexPath)) {
            abort(404);
        }

        $phpBinary = PHP_BINARY;
        $content = shell_exec(escapeshellarg($phpBinary) . ' ' . escapeshellarg($indexPath));

        if (!is_string($content)) {
            abort(500);
        }

        $baseHref = '/storage/' . trim($vcard->template_path, '/') . '/';
        $content = str_ireplace('<head>', '<head><base href="' . $baseHref . '">', $content);
        $csrfToken = csrf_token();
        $content = str_ireplace('</head>', '<meta name="csrf-token" content="' . htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') . '" /></head>', $content);
        $content = str_replace('window.__VCARD_SUBDOMAIN__ = "";', 'window.__VCARD_SUBDOMAIN__ = ' . json_encode($subdomain) . ';', $content);

        return response($content);
    }

    private function trackVisit(Vcard $vcard, Request $request): void
    {
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

        VcardVisit::create([
            'vcard_id'   => $vcard->id,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'page_url'   => $pageUrl,
            'referrer'   => $referrer,
            'browser'    => $agent->browser() ?: null,
            'device'     => $device,
            'platform'   => $agent->platform() ?: null,
            'country'    => null,
            'visited_at' => now(),
        ]);
    }
}
