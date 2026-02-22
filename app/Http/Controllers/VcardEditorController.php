<?php

namespace App\Http\Controllers;

use App\Models\Vcard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class VcardEditorController extends Controller
{
    public function edit(Request $request, string $subdomain): View
    {
        $vcard = $this->loadVcard($subdomain);
        $data = $this->loadJson($vcard);

        return view('vcards.editor', [
            'vcard' => $vcard,
            'sections' => $data,
        ]);
    }

    public function update(Request $request, string $subdomain): RedirectResponse
    {
        $vcard = $this->loadVcard($subdomain);
        $payload = $request->input('sections', []);
        $uploads = $request->file('uploads', []);

        if (!empty($uploads)) {
            $payload = $this->applyUploads($vcard, $payload, $uploads);
        }

        $this->storeJson($vcard, $payload);

        return back()->with('success', 'vCard data updated.');
    }

    private function loadVcard(string $subdomain): Vcard
    {
        $vcard = Vcard::where('subdomain', $subdomain)->firstOrFail();

        $user = Auth::user();
        $isAdmin = false;

        if ($user instanceof \App\Models\User && method_exists($user, 'hasRole')) {
            $isAdmin = $user->hasRole('admin');
        }

        if (!$isAdmin && $vcard->user_id && Auth::id() !== $vcard->user_id) {
            abort(403);
        }

        return $vcard;
    }

    private function loadJson(Vcard $vcard): array
    {
        if (!$vcard->data_path || !Storage::disk('public')->exists($vcard->data_path)) {
            return [];
        }

        $raw = Storage::disk('public')->get($vcard->data_path);
        $data = json_decode($raw, true);

        return is_array($data) ? $data : [];
    }

    private function storeJson(Vcard $vcard, array $payload): void
    {
        $json = json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $storageRoot = config('vcard.storage_root');

        $dataPath = $vcard->data_path ?: $storageRoot . '/' . $vcard->subdomain . '/data.json';
        Storage::disk('public')->put($dataPath, $json);

        $templateDefault = $storageRoot . '/' . $vcard->subdomain . '/template/default.json';
        Storage::disk('public')->put($templateDefault, $json);

        $vcard->update([
            'data_path' => $dataPath,
        ]);
    }

    private function applyUploads(Vcard $vcard, array $payload, array $uploads): array
    {
        foreach ($uploads as $key => $value) {
            if (is_array($value)) {
                $payload[$key] = $this->applyUploads($vcard, $payload[$key] ?? [], $value);
                continue;
            }

            if ($value && $value->isValid()) {
                $path = $value->store('vcards/' . $vcard->subdomain . '/uploads', 'public');
                $payload[$key] = Storage::disk('public')->url($path);
            }
        }

        return $payload;
    }
}
