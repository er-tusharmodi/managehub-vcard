<?php

namespace App\Http\Controllers;

use App\Models\Vcard;
use App\Models\VcardVisit;
use App\Repositories\Contracts\VcardContentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;
use Symfony\Component\HttpFoundation\Response;

class VcardPublicController extends Controller
{
    public function __construct(private readonly VcardContentRepository $contentRepository)
    {
    }

    public function show(Request $request, string $subdomain): Response
    {
        // Path-based access (vcard.managehub.in/{subdomain}) is admin-preview only
        if ($request->routeIs('vcard.public.path')) {
            $user = Auth::user();
            if (!$user || !($user->hasRole('admin') || $user->hasRole('super-admin'))) {
                return redirect('/');
            }
        }

        $vcard = Vcard::where('subdomain', $subdomain)->first();

        if (!$vcard) {
            $customPage = \App\Models\CustomPage::where('subdomain', $subdomain)
                ->where('status', 'active')
                ->first();

            if ($customPage) {
                return response($customPage->html_content ?? '', 200)
                    ->header('Content-Type', 'text/html; charset=utf-8');
            }

            abort(404);
        }

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

        // Asset base points to public/vcard-assets/{template_key}/
        $assetBase = asset('vcard-assets/' . $vcard->template_key) . '/';

        $templateView = 'vcards.templates.' . $vcard->template_key;

        return response()->view($templateView, compact('data', 'vcard', 'subdomain', 'assetBase'));
    }

    public function inactive(Request $request, string $subdomain): Response
    {
        $vcard = Vcard::where('subdomain', $subdomain)->firstOrFail();

        return response()->view('vcards.subscription-inactive', [
            'vcard' => $vcard,
        ], 403);
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
