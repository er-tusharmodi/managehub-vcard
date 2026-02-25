<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Vcard;
use App\Models\VcardBooking;
use App\Models\VcardContact;
use App\Models\VcardEnquiry;
use App\Models\VcardOrder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VcardSubmissionController extends Controller
{
    private const TYPE_MODEL_MAP = [
        'order' => VcardOrder::class,
        'booking' => VcardBooking::class,
        'enquiry' => VcardEnquiry::class,
        'contact' => VcardContact::class,
    ];

    public function index(Request $request, Vcard $vcard, string $type): View
    {
        if ($vcard->user_id !== $request->user()->id) {
            abort(403);
        }

        $type = strtolower($type);
        if (!array_key_exists($type, self::TYPE_MODEL_MAP)) {
            abort(404);
        }

        $modelClass = self::TYPE_MODEL_MAP[$type];

        $rows = $modelClass::query()
            ->where('vcard_id', $vcard->id)
            ->latest()
            ->paginate(20);

        $typeLabels = [
            'order' => 'Orders',
            'booking' => 'Bookings',
            'enquiry' => 'Enquiries',
            'contact' => 'Contacts',
        ];

        return view('client.submissions.index', [
            'vcard' => $vcard,
            'type' => $type,
            'typeLabel' => $typeLabels[$type] ?? ucfirst($type),
            'rows' => $rows,
        ]);
    }

    public function updateStatus(Request $request, Vcard $vcard, string $type, int $id)
    {
        if ($vcard->user_id !== $request->user()->id) {
            abort(403);
        }

        $type = strtolower($type);
        if (!array_key_exists($type, self::TYPE_MODEL_MAP)) {
            abort(404);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,accepted,declined',
        ]);

        $modelClass = self::TYPE_MODEL_MAP[$type];
        $submission = $modelClass::query()
            ->where('vcard_id', $vcard->id)
            ->findOrFail($id);

        $submission->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'status' => $validated['status'],
        ]);
    }
}
