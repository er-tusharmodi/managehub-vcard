<?php

namespace App\Http\Controllers;

use App\Models\Vcard;
use App\Services\VcardSubmissionStore;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VcardSubmissionController extends Controller
{
    private const ALLOWED_TYPES = [
        'order',
        'booking',
        'enquiry',
        'contact',
    ];

    public function submit(Request $request, string $subdomain, string $type, VcardSubmissionStore $store): JsonResponse
    {
        $type = strtolower($type);
        if (!in_array($type, self::ALLOWED_TYPES, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid submission type.',
            ], 404);
        }

        $vcard = Vcard::where('subdomain', $subdomain)->firstOrFail();

        if (!$vcard->isSubscriptionActive()) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription inactive. Please contact your service provider.',
            ], 403);
        }

        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'message' => ['nullable', 'string', 'max:5000'],
            'items' => ['nullable', 'array'],
            'items.*.label' => ['nullable', 'string', 'max:100'],
            'items.*.value' => ['nullable', 'string', 'max:500'],
            'total' => ['nullable', 'numeric', 'min:0'],
            'source_template' => ['nullable', 'string', 'max:255'],
        ]);

        $payload = $request->except(['_token']);

        $model = $store->store($type, $vcard, $payload, $validated);

        return response()->json([
            'success' => true,
            'id' => $model->getKey(),
            'type' => $type,
        ]);
    }
}
