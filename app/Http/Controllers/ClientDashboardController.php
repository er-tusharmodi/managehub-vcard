<?php

namespace App\Http\Controllers;

use App\Models\VcardBooking;
use App\Models\VcardContact;
use App\Models\VcardEnquiry;
use App\Models\VcardOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ClientDashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        
        // Get all vcards for the authenticated user
        $vcards = $user->vcards()->with('visits')->get();
        $vcardIds = $vcards->pluck('id')->toArray();

        // Calculate stats
        $totalVcards = $vcards->count();
        $activeVcards = $vcards->where('status', 'active')->count();
        $totalVisitors = $vcards->sum(fn($vcard) => $vcard->getTotalVisitors());
        $visitorsByVcard = $vcards->mapWithKeys(fn($vcard) => [
            $vcard->id => [
                'today' => $vcard->getTodayVisitors(),
                'week' => $vcard->getThisWeekVisitors(),
                'month' => $vcard->getThisMonthVisitors(),
                'total' => $vcard->getTotalVisitors(),
            ]
        ])->toArray();

        // Get joined date (first created vcard) and plan expiry
        $joinedDate = $vcards->min('created_at');
        $plannedExpiry = $vcards->max('subscription_expires_at');

        // Get submission stats
        $today = today();
        $todayStart = $today->startOfDay();
        $todayEnd = $today->copy()->endOfDay();
        
        $submissionStats = [
            'orders' => [
                'total' => VcardOrder::whereIn('vcard_id', $vcardIds)->count(),
                'today' => VcardOrder::whereIn('vcard_id', $vcardIds)->whereBetween('created_at', [$todayStart, $todayEnd])->count(),
                'week' => VcardOrder::whereIn('vcard_id', $vcardIds)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'revenue' => VcardOrder::whereIn('vcard_id', $vcardIds)->sum('total'),
            ],
            'bookings' => [
                'total' => VcardBooking::whereIn('vcard_id', $vcardIds)->count(),
                'today' => VcardBooking::whereIn('vcard_id', $vcardIds)->whereBetween('created_at', [$todayStart, $todayEnd])->count(),
                'week' => VcardBooking::whereIn('vcard_id', $vcardIds)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            ],
            'enquiries' => [
                'total' => VcardEnquiry::whereIn('vcard_id', $vcardIds)->count(),
                'today' => VcardEnquiry::whereIn('vcard_id', $vcardIds)->whereBetween('created_at', [$todayStart, $todayEnd])->count(),
                'week' => VcardEnquiry::whereIn('vcard_id', $vcardIds)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            ],
            'contacts' => [
                'total' => VcardContact::whereIn('vcard_id', $vcardIds)->count(),
                'today' => VcardContact::whereIn('vcard_id', $vcardIds)->whereBetween('created_at', [$todayStart, $todayEnd])->count(),
                'week' => VcardContact::whereIn('vcard_id', $vcardIds)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            ],
        ];

        // Determine which submission types are active
        $activeSubmissionTypes = [];
        foreach ($vcards as $vcard) {
            $templatePath = $vcard->template_path ? trim($vcard->template_path, '/') : '';
            $defaultJsonPath = $templatePath ? Storage::disk('public')->path($templatePath . '/default.json') : '';

            if ($defaultJsonPath && is_readable($defaultJsonPath)) {
                $data = json_decode(file_get_contents($defaultJsonPath), true);
                if (is_array($data)) {
                    if (isset($data['cart']) || isset($data['products'])) {
                        $activeSubmissionTypes['orders'] = true;
                    }
                    if (isset($data['booking']) || isset($data['reservation'])) {
                        $activeSubmissionTypes['bookings'] = true;
                    }
                    if (isset($data['enquiryForm']) || isset($data['enquiry'])) {
                        $activeSubmissionTypes['enquiries'] = true;
                    }
                    if (isset($data['contactForm']) || isset($data['contact'])) {
                        $activeSubmissionTypes['contacts'] = true;
                    }
                }
            }
        }

        return view('client.dashboard', [
            'user' => $user,
            'vcards' => $vcards,
            'totalVcards' => $totalVcards,
            'activeVcards' => $activeVcards,
            'totalVisitors' => $totalVisitors,
            'visitorsByVcard' => $visitorsByVcard,
            'joinedDate' => $joinedDate,
            'plannedExpiry' => $plannedExpiry,
            'submissionStats' => $submissionStats,
            'activeSubmissionTypes' => $activeSubmissionTypes,
        ]);
    }
}
