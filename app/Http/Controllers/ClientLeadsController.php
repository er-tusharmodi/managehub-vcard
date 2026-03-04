<?php

namespace App\Http\Controllers;

use App\Models\Vcard;
use App\Models\VcardOrder;
use App\Models\VcardBooking;
use App\Models\VcardEnquiry;
use App\Models\VcardContact;
use App\Models\Mongo\VcardSubmission;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientLeadsController extends Controller
{
    private const TABS = ['order', 'booking', 'enquiry', 'contact'];

    private const TAB_LABELS = [
        'order'   => 'Orders',
        'booking' => 'Bookings',
        'enquiry' => 'Enquiries',
        'contact' => 'Contacts',
    ];

    private const TAB_MODELS = [
        'order'   => VcardOrder::class,
        'booking' => VcardBooking::class,
        'enquiry' => VcardEnquiry::class,
        'contact' => VcardContact::class,
    ];

    private const TAB_ICONS = [
        'order'   => 'mdi-cart-outline',
        'booking' => 'mdi-calendar-check-outline',
        'enquiry' => 'mdi-help-circle-outline',
        'contact' => 'mdi-message-text-outline',
    ];

    private const TAB_COLORS = [
        'order'   => '#ef4444',
        'booking' => '#3b82f6',
        'enquiry' => '#f59e0b',
        'contact' => '#10b981',
    ];

    /** Returns true when VCARD_STORAGE_MODE uses the unified VcardSubmission collection. */
    private function usesUnifiedCollection(): bool
    {
        $mode = (string) config('app.vcard_storage_mode', 'file_only');
        return in_array($mode, ['mongo_only', 'mongo_preferred'], true);
    }

    public function index(Request $request, string $subdomain): View
    {
        $user  = $request->user();
        $vcard = Vcard::where('subdomain', $subdomain)->firstOrFail();

        if ((string) $vcard->user_id !== (string) $user->id) {
            abort(403);
        }

        $activeTab = $request->query('tab', 'order');
        if (!in_array($activeTab, self::TABS, true)) {
            $activeTab = 'order';
        }

        $unified = $this->usesUnifiedCollection();

        // Counts for all tabs (badges)
        $counts = [];
        foreach (self::TABS as $tab) {
            if ($unified) {
                $counts[$tab] = VcardSubmission::where(function ($q) use ($vcard) {
                        $q->where('legacy_vcard_id', (string) $vcard->id)
                          ->orWhere('subdomain', $vcard->subdomain);
                    })
                    ->where('submission_type', $tab)
                    ->count();
            } else {
                $counts[$tab] = self::TAB_MODELS[$tab]::where('vcard_id', $vcard->id)->count();
            }
        }

        // Paginated rows for the active tab only
        if ($unified) {
            $rows = VcardSubmission::where(function ($q) use ($vcard) {
                    $q->where('legacy_vcard_id', (string) $vcard->id)
                      ->orWhere('subdomain', $vcard->subdomain);
                })
                ->where('submission_type', $activeTab)
                ->latest('submitted_at')
                ->paginate(20)
                ->withQueryString();
        } else {
            $rows = self::TAB_MODELS[$activeTab]::query()
                ->where('vcard_id', $vcard->id)
                ->latest()
                ->paginate(20)
                ->withQueryString();
        }

        return view('client.leads.index', [
            'vcard'      => $vcard,
            'activeTab'  => $activeTab,
            'counts'     => $counts,
            'rows'       => $rows,
            'tabLabels'  => self::TAB_LABELS,
            'tabIcons'   => self::TAB_ICONS,
            'tabColors'  => self::TAB_COLORS,
        ]);
    }
}

