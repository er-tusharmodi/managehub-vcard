<?php

namespace App\Http\Controllers;

use App\Models\Vcard;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ClientVcardEditorController extends Controller
{
    public function edit(string $subdomain, string $section = null): View
    {
        $vcard = Vcard::where('subdomain', $subdomain)->firstOrFail();

        // Verify the vcard belongs to the authenticated user
        if ($vcard->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this vCard');
        }

        return view('client.vcards.editor', [
            'vcard' => $vcard,
            'section' => $section,
        ]);
    }
}
