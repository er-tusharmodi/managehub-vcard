<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\UsernameGenerator;
use App\Mail\VcardCredentialsMail;
use App\Models\User;
use App\Models\Vcard;
use App\Services\VcardTemplateService;
use App\Services\QrCodeService;
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
    public function index(Request $request, VcardTemplateService $templates): View
    {
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $validSorts = ['subdomain', 'client_name', 'status', 'subscription_status', 'created_at'];
        $validDirections = ['asc', 'desc'];
        
        if (!in_array($sort, $validSorts)) {
            $sort = 'created_at';
        }
        if (!in_array($direction, $validDirections)) {
            $direction = 'desc';
        }
        
        $vcards = Vcard::orderBy($sort, $direction)
            ->paginate(15)
            ->appends(request()->query());
        
        return view('admin.vcards.index', [
            'vcards' => $vcards,
            'templates' => $templates->listTemplates(),
            'baseDomain' => config('vcard.base_domain'),
            'sort' => $sort,
            'direction' => $direction,
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
            'subscription_status' => ['required', 'in:active,inactive'],
            'subscription_expires_at' => ['nullable', 'date', 'after_or_equal:today'],
            'send_credentials' => ['nullable', 'boolean'],
        ], [
            'subdomain.unique' => 'This subdomain is already taken. Please choose a different one.',
            'subdomain.regex' => 'Subdomain can only contain lowercase letters, numbers, and hyphens.',
        ]);

        $template = $templates->templatePath($validated['template_key']);
        if (!$template) {
            return back()->withErrors(['template_key' => 'Template not found.'])->withInput();
        }

        $password = Str::random(12);
        $username = UsernameGenerator::generateFromSubdomain($validated['subdomain']);
        
        $user = User::firstOrCreate([
            'email' => $validated['client_email'],
        ], [
            'name' => $validated['client_name'],
            'username' => $username,
            'password' => Hash::make($password),
        ]);

        if (!$user->wasRecentlyCreated) {
            $user->forceFill([
                'name' => $validated['client_name'],
                'username' => $username,
                'password' => Hash::make($password),
            ])->save();
        }

        if (method_exists($user, 'assignRole')) {
            $user->assignRole('client');
        }

        // Double check subdomain uniqueness before creating
        if (Vcard::where('subdomain', Str::lower($validated['subdomain']))->exists()) {
            return back()
                ->withErrors(['subdomain' => 'This subdomain is already taken. Please choose a different one.'])
                ->withInput();
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
            'subscription_status' => $validated['subscription_status'],
            'subscription_started_at' => $validated['subscription_status'] === 'active' ? now() : null,
            'subscription_expires_at' => $validated['subscription_expires_at'] ?? null,
            'created_by' => Auth::id(),
        ]);

        $this->publishTemplateAssets($vcard, $templates);

        // Generate QR code for the vCard
        $qrCodeService = app(QrCodeService::class);
        $qrCodeService->generateVcardQr($vcard);
        
        // Update data.json with QR code URL
        $this->updateDataJsonWithQrCode($vcard, $qrCodeService);

        $sendCredentials = (bool) ($validated['send_credentials'] ?? false);
        if ($sendCredentials) {
            $this->sendCredentials($user, $password, $vcard);
        }

        $domain = config('vcard.base_domain');
        $target = config('vcard.cname_target');
        return redirect()
            ->route('admin.vcards.index')
            ->with('success', "vCard created! Add DNS A record: {$vcard->subdomain}.{$domain} → {$target}")
            ->with('credentials_sent', $sendCredentials);
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
            'subscription_status' => ['required', 'in:active,inactive'],
            'subscription_expires_at' => ['nullable', 'date'],
        ]);

        $subscriptionStatus = $validated['subscription_status'];
        $expiresAt = $validated['subscription_expires_at'] ?? null;

        if ($subscriptionStatus === 'inactive' && $expiresAt && now()->lt($expiresAt)) {
            $subscriptionStatus = 'active';
        }

        $subscriptionStartedAt = $vcard->subscription_started_at;
        if ($subscriptionStatus === 'active' && !$subscriptionStartedAt) {
            $subscriptionStartedAt = now();
        }

        $vcard->update([
            'client_name' => $validated['client_name'],
            'client_email' => $validated['client_email'],
            'client_phone' => $validated['client_phone'] ?? null,
            'client_address' => $validated['client_address'] ?? null,
            'subscription_status' => $subscriptionStatus,
            'subscription_started_at' => $subscriptionStartedAt,
            'subscription_expires_at' => $expiresAt,
        ]);

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
        
        // Set the vCard's public URL in the data
        $vcardUrl = 'https://' . $vcard->subdomain . '.' . config('vcard.base_domain');
        $this->setWebsiteUrl($defaultData, $vcardUrl);
        
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
        try {
            $baseDomain = config('vcard.base_domain');
            $loginUrl = 'https://' . $vcard->subdomain . '.' . $baseDomain . '/login';
            $vcardUrl = 'https://' . $vcard->subdomain . '.' . $baseDomain . '/';

            \Log::info('Preparing to send email to: ' . $user->email);
            Mail::to($user->email)->send(new VcardCredentialsMail($user, $password, $loginUrl, $vcardUrl));
            \Log::info('Email queued/sent successfully to: ' . $user->email);
        } catch (\Exception $e) {
            \Log::error('Error sending credentials email: ' . $e->getMessage(), ['exception' => $e]);
            throw $e;
        }
    }

    public function shareVcard(Vcard $vcard)
    {
        if (!$vcard->user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Generate a temporary password for sharing
        $tempPassword = Str::random(12);
        $vcard->user->update([
            'password' => Hash::make($tempPassword),
        ]);

        return response()->json([
            'username' => $vcard->user->username,
            'email' => $vcard->user->email,
            'clientName' => $vcard->client_name,
            'password' => $tempPassword,
        ]);
    }

    public function regeneratePassword(Vcard $vcard)
    {
        if (!$vcard->user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $newPassword = Str::random(12);
        $vcard->user->update([
            'password' => Hash::make($newPassword),
        ]);

        return response()->json([
            'password' => $newPassword,
            'message' => 'Password regenerated successfully',
        ]);
    }

    public function sendCredentialsToClient(Request $request, Vcard $vcard)
    {
        try {
            $request->validate([
                'password' => ['required', 'string'],
            ]);

            if (!$vcard->user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            // Send credentials
            \Log::info('Sending credentials for vCard: ' . $vcard->subdomain . ' to ' . $vcard->user->email);
            $this->sendCredentials($vcard->user, $request->password, $vcard);
            \Log::info('Credentials sent successfully for vCard: ' . $vcard->subdomain);

            return response()->json([
                'message' => 'Credentials sent to client successfully',
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send credentials: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to send credentials: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update data.json with QR code URL
     */
    private function updateDataJsonWithQrCode(Vcard $vcard, QrCodeService $qrCodeService): void
    {
        $data = $this->loadJson($vcard);
        
        // Get QR code URL
        $qrCodeUrl = $qrCodeService->getQrCodeUrl($vcard);
        
        // Add QR code URL to assets section
        if (!isset($data['assets'])) {
            $data['assets'] = [];
        }
        
        $data['assets']['qrCodeImage'] = $qrCodeUrl;
        
        // Ensure website URL is set correctly
        $vcardUrl = 'https://' . $vcard->subdomain . '.' . config('vcard.base_domain');
        $this->setWebsiteUrl($data, $vcardUrl);
        
        // Save updated data
        $this->storeJson($vcard, $data);
    }
    
    /**
     * Set website URL in data structure (works with different template structures)
     */
    private function setWebsiteUrl(array &$data, string $url): void
    {
        // Check common locations where website URL might be stored
        if (isset($data['shop'])) {
            $data['shop']['website'] = $url;
        }
        if (isset($data['doctor'])) {
            $data['doctor']['website'] = $url;
        }
        if (isset($data['R'])) {
            $data['R']['website'] = $url;
        }
        if (isset($data['business'])) {
            $data['business']['website'] = $url;
        }
    }

    /**
     * Run sync sections config command
     */
    public function syncSections(Request $request)
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('vcards:sync-sections');
            $output = \Illuminate\Support\Facades\Artisan::output();
            
            $message = 'Sections synced successfully!';
            
            // Return JSON for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            }
            
            // Return redirect for form submissions
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            $errorMessage = 'Sync failed: ' . $e->getMessage();
            
            // Return JSON for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            
            // Return redirect for form submissions
            return redirect()->back()->with('error', $errorMessage);
        }
    }
}
