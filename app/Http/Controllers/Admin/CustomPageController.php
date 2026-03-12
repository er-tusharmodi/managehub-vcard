<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomPage;
use App\Models\Vcard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CustomPageController extends Controller
{
    public function index(): View
    {
        $pages = CustomPage::orderBy('created_at', 'desc')->paginate(15);

        return view('admin.custom-pages.index', [
            'pages'      => $pages,
            'baseDomain' => config('vcard.base_domain'),
        ]);
    }

    public function create(): View
    {
        return view('admin.custom-pages.create', [
            'baseDomain' => config('vcard.base_domain'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subdomain' => [
                'required',
                'string',
                'max:60',
                'regex:/^[a-z0-9]([a-z0-9-]*[a-z0-9])?$/',
            ],
            'title'     => ['nullable', 'string', 'max:255'],
            'html_file' => ['required', 'file', 'mimes:html,htm', 'max:5120'],
            'status'    => ['required', 'in:active,inactive'],
        ], [
            'subdomain.regex'  => 'Subdomain can only contain lowercase letters, numbers, and hyphens.',
            'html_file.mimes'  => 'Only .html or .htm files are allowed.',
            'html_file.max'    => 'HTML file must not exceed 5 MB.',
        ]);

        $subdomain = Str::lower($validated['subdomain']);

        if (Vcard::where('subdomain', $subdomain)->exists()) {
            return back()->withErrors(['subdomain' => 'This subdomain is already used by a vCard.'])->withInput();
        }

        if (CustomPage::where('subdomain', $subdomain)->exists()) {
            return back()->withErrors(['subdomain' => 'This subdomain is already taken by another custom page.'])->withInput();
        }

        $html = $this->processHtml($request->file('html_file')->get());

        CustomPage::create([
            'subdomain'    => $subdomain,
            'title'        => $validated['title'] ?: $subdomain,
            'html_content' => $html,
            'status'       => $validated['status'],
            'created_by'   => Auth::id(),
        ]);

        return redirect()->route('admin.custom-pages.index')
            ->with('success', "Custom page created! Live at: https://{$subdomain}." . config('vcard.base_domain'));
    }

    public function edit(string $id): View
    {
        $page = CustomPage::findOrFail($id);

        return view('admin.custom-pages.edit', [
            'page'       => $page,
            'baseDomain' => config('vcard.base_domain'),
        ]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $page = CustomPage::findOrFail($id);

        $validated = $request->validate([
            'title'     => ['nullable', 'string', 'max:255'],
            'html_file' => ['nullable', 'file', 'mimes:html,htm', 'max:5120'],
            'status'    => ['required', 'in:active,inactive'],
        ], [
            'html_file.mimes' => 'Only .html or .htm files are allowed.',
            'html_file.max'   => 'HTML file must not exceed 5 MB.',
        ]);

        $updateData = [
            'title'  => $validated['title'] ?: $page->subdomain,
            'status' => $validated['status'],
        ];

        if ($request->hasFile('html_file')) {
            $updateData['html_content'] = $this->processHtml($request->file('html_file')->get());
        }

        $page->update($updateData);

        return redirect()->route('admin.custom-pages.index')
            ->with('success', 'Custom page updated successfully.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $page = CustomPage::findOrFail($id);
        $subdomain = $page->subdomain;
        $page->delete();

        return redirect()->route('admin.custom-pages.index')
            ->with('success', "Custom page \"{$subdomain}\" deleted.");
    }

    /**
     * Strip PHP execution tags from uploaded HTML for security.
     */
    private function processHtml(string $html): string
    {
        // Remove php opening/closing tags and short echo tags
        $html = preg_replace('/<\?(?:php|=)?.+?\?>/si', '', $html);
        // Remove any remaining unclosed opening tags
        $html = preg_replace('/<\?(?:php|=)?/si', '', $html);

        return $html;
    }
}
