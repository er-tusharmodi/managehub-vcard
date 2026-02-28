<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VcardOrder;
use App\Models\VcardVisit;
use App\Services\VisitorAnalyticsService;
use Carbon\Carbon;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(VisitorAnalyticsService $analytics): View
    {
        $totalVisits = VcardVisit::count();
        $totalUsers = User::count();
        $totalOrders = VcardOrder::count();
        $conversionRate = $totalVisits > 0
            ? round(($totalOrders / $totalVisits) * 100, 2)
            : 0;
        $avgDailyVisits = (int) round($totalVisits / 30);

        $last30Days = [];
        $last30Visits = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $last30Days[] = $date->format('M d');
            
            // MongoDB date filtering
            $startOfDay = $date->startOfDay();
            $endOfDay = $date->copy()->endOfDay();
            $last30Visits[] = VcardVisit::whereBetween('visited_at', [$startOfDay, $endOfDay])->count();
        }

        $monthLabels = [];
        $monthlySales = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthLabels[] = $date->format('M');
            
            // MongoDB date filtering  
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            $monthlySales[] = (float) VcardOrder::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->get()
                ->sum('total');
        }

        $topPages = $analytics->topPages(7);
        $trafficSources = $analytics->trafficSources(7);
        $deviceBreakdown = $analytics->deviceBreakdown();
        $browserBreakdown = $analytics->browserBreakdown();

        return view('admin.dashboard', [
            'totalVisits' => $totalVisits,
            'totalUsers' => $totalUsers,
            'conversionRate' => $conversionRate,
            'avgDailyVisits' => $avgDailyVisits,
            'last30Days' => $last30Days,
            'last30Visits' => $last30Visits,
            'monthLabels' => $monthLabels,
            'monthlySales' => $monthlySales,
            'topPages' => $topPages,
            'trafficSources' => $trafficSources,
            'deviceBreakdown' => $deviceBreakdown,
            'browserBreakdown' => $browserBreakdown,
        ]);
    }
}
