<?php

namespace App\Http\Controllers;

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

        $view = $page->slug === 'home' ? 'frontend.index' : 'frontend.page';

        return view($view, [
            'page' => $page,
            'pages' => $pages,
            'settings' => $settings,
        ]);
    }
}
