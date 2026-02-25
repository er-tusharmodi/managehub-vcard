<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\WebsitePage;
use App\Models\WebsiteSetting;
use Illuminate\View\View;

class WebsiteController extends Controller
{
    public function show(?WebsitePage $page = null): View
    {
        $page = $page ?? WebsitePage::where('slug', 'home')->firstOrFail();

        $settings = WebsiteSetting::pluck('value', 'key')->toArray();

        $pages = WebsitePage::orderBy('title')->get();

        // Get visible templates for home page vCard Previews section
        $templates = Template::visible()->ordered()->get()->map(function ($template) {
            return [
                'id' => $template->id,
                'template_key' => $template->template_key,
                'title' => $template->display_name,
                'category' => $template->category ?? 'General',
                'preview_url' => route('admin.templates.preview', $template->template_key),
                'asset_base_url' => $template->asset_base_url,
            ];
        });

        $view = $page->slug === 'home' ? 'frontend.index' : 'frontend.page';

        return view($view, [
            'page' => $page,
            'pages' => $pages,
            'settings' => $settings,
            'templates' => $templates, // Add templates for home page
        ]);
    }
}
