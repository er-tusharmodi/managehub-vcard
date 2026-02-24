<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ClientDashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        
        // Get all vcards for the authenticated user
        $vcards = $user->vcards()->with('visits')->get();

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

        return view('client.dashboard', [
            'user' => $user,
            'vcards' => $vcards,
            'totalVcards' => $totalVcards,
            'activeVcards' => $activeVcards,
            'totalVisitors' => $totalVisitors,
            'visitorsByVcard' => $visitorsByVcard,
            'joinedDate' => $joinedDate,
            'plannedExpiry' => $plannedExpiry,
        ]);
    }
}
