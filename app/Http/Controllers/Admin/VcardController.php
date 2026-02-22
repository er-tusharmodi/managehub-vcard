<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\VcardCredentialsMail;
use App\Models\User;
use App\Models\Vcard;
use App\Services\VcardTemplateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class VcardController extends Controller
{
    public function index(VcardTemplateService $templates): View
    {
        return view('admin.vcards.index', [
            'vcards' => Vcard::latest()->get(),
            'templates' => $templates->listTemplates(),
            'baseDomain' => config('vcard.base_domain'),
        ]);
    }

    public function create(VcardTemplateService $templates): View
    {
        return view('admin.vcards.create', [
            'templates' => $templates->listTemplates(),
            'baseDomain' => config('vcard.base_domain'),
        ]);
    }

    public function store(Request $request, VcardTemplateService $templates): RedirectResponse
    {
        $validated = $request->validate([
            'client_name' => ['required', 'string', 'max:255'],
            'client_email' => ['required', 'email', 'max:255'],
            'client_phone' => ['nullable', 'string', 'max:50'],
            'client_address' => ['nullable', 'string', 'max:255'],
            'subdomain' => ['required', 'string', 'max:60', 'regex:/^[a-z0-9]([a-z0-9-]*[a-z0-9])?$/', 'unique:vcards,subdomain'],
            'template_key' => ['required', 'string'],
        ]);

        $template = $templates->templatePath($validated['template_key']);
        if (!$template) {
            return back()->withErrors(['template_key' => 'Template not found.'])->withInput();
        }

        $password = Str::random(12);
        $user = User::firstOrCreate([
            'email' => $validated['client_email'],
        ], [
            'name' => $validated['client_name'],
            'password' => Hash::make($password),
        ]);

        if (!$user->wasRecentlyCreated) {
            $user->forceFill([
                'name' => $validated['client_name'],
                'password' => Hash::make($password),
            ])->save();
        }

        if (method_exists($user, 'assignRole') && class_exists(\Spatie\Permission\Models\Role::class)) {
            $roleExists = \Spatie\Permission\Models\Role::where('name', 'client')->exists();
            if ($roleExists) {
                $user->assignRole('client');
            }
        }

        $vcard = Vcard::create([
            'user_id' => $user->id,
            'subdomain' => Str::lower($validated['subdomain']),
            'template_key' => $validated['template_key'],
            'client_name' => $validated['client_name'],
            'client_email' => $validated['client_email'],
            'client_phone' => $validated['client_phone'] ?? null,
            'client_address' => $validated['client_address'] ?? null,
            'status' => 'active',
            'created_by' => Auth::id(),
        ]);

        $this->publishTemplateAssets($vcard, $templates);

        $this->sendCredentials($user, $password, $vcard);

        $domain = config('vcard.base_domain');
        $target = config('vcard.cname_target');
        return redirect()->route('admin.vcards.index')->with('success', "vCard created! Add DNS A record: {$vcard->subdomain}.{$domain} → {$target}");
    }

    public function destroy(Vcard $vcard): RedirectResponse
    {
        if ($vcard->template_path) {
            Storage::disk('public')->deleteDirectory($vcard->template_path);
        }
        
        if ($vcard->data_path) {
            Storage::disk('public')->delete($vcard->data_path);
        }

        $storageRoot = config('vcard.storage_root');
        Storage::disk('public')->deleteDirectory($storageRoot . '/' . $vcard->subdomain);

        $vcard->delete();

        return redirect()->route('admin.vcards.index')->with('success', 'vCard deleted successfully.');
    }

    public function edit(Vcard $vcard, VcardTemplateService $templates): View
    {
        return view('admin.vcards.edit', [
            'vcard' => $vcard,
            'templates' => $templates->listTemplates(),
            'baseDomain' => config('vcard.base_domain'),
        ]);
    }

    public function update(Request $request, Vcard $vcard): RedirectResponse
    {
        $validated = $request->validate([
            'client_name' => ['required', 'string', 'max:255'],
            'client_email' => ['required', 'email', 'max:255'],
            'client_phone' => ['nullable', 'string', 'max:50'],
            'client_address' => ['nullable', 'string', 'max:255'],
        ]);

        $vcard->update($validated);

        // Update the user details as well
        if ($vcard->user_id) {
            $vcard->user->update([
                'name' => $validated['client_name'],
                'email' => $validated['client_email'],
            ]);
        }

        return redirect()->route('admin.vcards.index')->with('success', 'vCard updated successfully.');
    }

    public function updateStatus(Request $request, Vcard $vcard): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:draft,pending_verification,active'],
        ]);

        $vcard->update(['status' => $validated['status']]);

        return back()->with('success', 'Status updated to ' . ucfirst(str_replace('_', ' ', $validated['status'])) . '.');
    }

    public function editData(Vcard $vcard): View
    {
        return view('admin.vcards.data', [
            'vcard' => $vcard,
            'sections' => $this->loadJson($vcard),
            'baseDomain' => config('vcard.base_domain'),
        ]);
    }

    public function updateData(Request $request, Vcard $vcard): RedirectResponse
    {
        $payload = $request->input('sections', []);

        $uploads = $request->file('uploads', []);
        if (!empty($uploads)) {
            $payload = $this->applyUploads($vcard, $payload, $uploads);
        }

        $this->storeJson($vcard, $payload);

        return back()->with('success', 'vCard data updated.');
    }

    private function publishTemplateAssets(Vcard $vcard, VcardTemplateService $templates): void
    {
        $templatePath = $templates->templatePath($vcard->template_key);
        if (!$templatePath) {
            return;
        }

        $storageRoot = config('vcard.storage_root');
        $basePath = $storageRoot . '/' . $vcard->subdomain . '/template';

        Storage::disk('public')->deleteDirectory($basePath);
        Storage::disk('public')->makeDirectory($basePath);

        $targetPath = Storage::disk('public')->path($basePath);
        \Illuminate\Support\Facades\File::copyDirectory($templatePath, $targetPath);

        $defaultData = $templates->loadDefaultJson($vcard->template_key);
        $dataJson = json_encode($defaultData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        Storage::disk('public')->put($basePath . '/default.json', $dataJson);
        Storage::disk('public')->put($storageRoot . '/' . $vcard->subdomain . '/data.json', $dataJson);

        $vcard->update([
            'template_path' => $basePath,
            'data_path' => $storageRoot . '/' . $vcard->subdomain . '/data.json',
        ]);
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

    private function sendCredentials(User $user, string $password, Vcard $vcard): void
    {
        $baseDomain = config('vcard.base_domain');
        $loginUrl = 'http://' . $vcard->subdomain . '.' . $baseDomain . ':8000/login';
        $vcardUrl = 'http://' . $vcard->subdomain . '.' . $baseDomain . ':8000/';

        Mail::to($user->email)->send(new VcardCredentialsMail($user, $password, $loginUrl, $vcardUrl));
    }
}
