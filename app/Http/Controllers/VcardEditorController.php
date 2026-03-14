<?php

namespace App\Http\Controllers;

use App\Models\Vcard;
use App\Repositories\Contracts\VcardContentRepository;
use App\Traits\CompressesImages;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class VcardEditorController extends Controller
{
    use CompressesImages;

    public function __construct(private readonly VcardContentRepository $contentRepository)
    {
    }

    public function edit(Request $request, string $subdomain): View
    {
        $vcard = $this->loadVcard($subdomain);
        $data = $this->contentRepository->load($vcard);

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

        $this->contentRepository->save($vcard, $payload);

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

    private function applyUploads(Vcard $vcard, array $payload, array $uploads): array
    {
        foreach ($uploads as $key => $value) {
            if (is_array($value)) {
                $payload[$key] = $this->applyUploads($vcard, $payload[$key] ?? [], $value);
                continue;
            }

            if ($value && $value->isValid()) {
                $path = $this->storeUploadedImage($value, 'vcards/' . $vcard->subdomain . '/uploads');
                $payload[$key] = '/storage/' . $path;
            }
        }

        return $payload;
    }
}
