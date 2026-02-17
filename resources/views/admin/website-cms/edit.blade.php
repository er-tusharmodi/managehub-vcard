@extends('admin.layouts.app')

@section('title', 'Website CMS')

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Website CMS</h4>
        </div>

        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                <li class="breadcrumb-item active">Website CMS</li>
            </ol>
        </div>
    </div>

    @if (session('status') === 'website-cms-updated')
        <div class="alert alert-success">Website settings updated.</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.website-cms.update', $page) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">General</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="branding-tab" data-bs-toggle="tab" data-bs-target="#branding" type="button" role="tab">Branding</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="social-tab" data-bs-toggle="tab" data-bs-target="#social" type="button" role="tab">Social</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button" role="tab">SEO</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="hero-tab" data-bs-toggle="tab" data-bs-target="#hero" type="button" role="tab">Hero</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories" type="button" role="tab">Categories</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="vcard-tab" data-bs-toggle="tab" data-bs-target="#vcard" type="button" role="tab">vCard Preview</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="how-tab" data-bs-toggle="tab" data-bs-target="#how" type="button" role="tab">How It Works</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="cta-tab" data-bs-toggle="tab" data-bs-target="#cta" type="button" role="tab">CTA</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="footer-tab" data-bs-toggle="tab" data-bs-target="#footer" type="button" role="tab">Footer</button>
                    </li>
                </ul>

                <style>
                    .tab-pane.fade:not(.show) {
                        display: none !important;
                    }
                </style>

                <div class="tab-content pt-3">
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="site_name">Site Name</label>
                                    <input type="text" id="site_name" name="site_name" class="form-control" value="{{ old('site_name', $settings['site_name']) }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="site_tagline">Tagline</label>
                                    <input type="text" id="site_tagline" name="site_tagline" class="form-control" value="{{ old('site_tagline', $settings['site_tagline']) }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="site_url">Website URL</label>
                                    <input type="url" id="site_url" name="site_url" class="form-control" value="{{ old('site_url', $settings['site_url']) }}" placeholder="https://example.com">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="contact_email">Contact Email</label>
                                    <input type="email" id="contact_email" name="contact_email" class="form-control" value="{{ old('contact_email', $settings['contact_email']) }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="contact_phone">Contact Phone</label>
                                    <input type="text" id="contact_phone" name="contact_phone" class="form-control" value="{{ old('contact_phone', $settings['contact_phone']) }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="contact_address">Address</label>
                                    <input type="text" id="contact_address" name="contact_address" class="form-control" value="{{ old('contact_address', $settings['contact_address']) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="branding" role="tabpanel">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="primary_color">Primary Color</label>
                                    <input type="color" id="primary_color" name="primary_color" class="form-control form-control-color" value="{{ old('primary_color', $settings['primary_color']) }}" style="height: 40px;">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="secondary_color">Secondary Color</label>
                                    <input type="color" id="secondary_color" name="secondary_color" class="form-control form-control-color" value="{{ old('secondary_color', $settings['secondary_color']) }}" style="height: 40px;">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="logo">Logo</label>
                                    <input type="file" id="logo" name="logo" class="form-control" accept="image/*">
                                    @if (!empty($settings['logo_path']))
                                        <img src="{{ Storage::url($settings['logo_path']) }}" alt="Logo" class="img-fluid mt-2" style="max-height: 80px;">
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="favicon">Favicon</label>
                                    <input type="file" id="favicon" name="favicon" class="form-control" accept="image/*">
                                    @if (!empty($settings['favicon_path']))
                                        <img src="{{ Storage::url($settings['favicon_path']) }}" alt="Favicon" class="img-fluid mt-2" style="max-height: 48px;">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="social" role="tabpanel">
                        @php
                            $socialPlatforms = [
                                ['name' => 'Facebook', 'icon' => 'fab fa-facebook', 'key' => 'facebook'],
                                ['name' => 'Instagram', 'icon' => 'fab fa-instagram', 'key' => 'instagram'],
                                ['name' => 'Twitter / X', 'icon' => 'fab fa-x-twitter', 'key' => 'twitter'],
                                ['name' => 'LinkedIn', 'icon' => 'fab fa-linkedin', 'key' => 'linkedin'],
                                ['name' => 'YouTube', 'icon' => 'fab fa-youtube', 'key' => 'youtube'],
                                ['name' => 'TikTok', 'icon' => 'fab fa-tiktok', 'key' => 'tiktok'],
                                ['name' => 'GitHub', 'icon' => 'fab fa-github', 'key' => 'github'],
                                ['name' => 'Dribbble', 'icon' => 'fab fa-dribbble', 'key' => 'dribbble'],
                            ];
                            $existingSocials = [];
                            foreach ($socialPlatforms as $platform) {
                                $key = 'social_' . $platform['key'];
                                if (!empty($settings[$key])) {
                                    $existingSocials[] = [
                                        'name' => $platform['name'],
                                        'url' => $settings[$key],
                                        'icon' => $platform['icon'],
                                        'key' => $platform['key']
                                    ];
                                }
                            }
                        @endphp
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Social Media Links</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" data-add-item="social-links">Add Social Link</button>
                        </div>
                        <div id="social-links-list" data-collection>
                            @foreach ($existingSocials as $index => $social)
                                <div class="border rounded-3 p-3 mb-3" data-item>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong><i class="{{ $social['icon'] }}"></i> {{ $social['name'] }}</strong>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-remove-item>Remove</button>
                                    </div>
                                    <input type="text" class="form-control" name="social_links[{{ $index }}][url]" value="{{ $social['url'] }}" placeholder="https://example.com">
                                    <input type="hidden" name="social_links[{{ $index }}][key]" value="{{ $social['key'] }}">
                                    <input type="hidden" name="social_links[{{ $index }}][name]" value="{{ $social['name'] }}">
                                    <input type="hidden" name="social_links[{{ $index }}][icon]" value="{{ $social['icon'] }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="tab-pane fade" id="seo" role="tabpanel">
                        <div class="mb-3">
                            <label class="form-label" for="seo_title">Site SEO Title</label>
                            <input type="text" id="seo_title" name="seo_title" class="form-control" value="{{ old('seo_title', $settings['seo_title']) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="seo_description">Site SEO Description</label>
                            <textarea id="seo_description" name="seo_description" class="form-control" rows="3">{{ old('seo_description', $settings['seo_description']) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="seo_keywords">Site SEO Keywords</label>
                            <textarea id="seo_keywords" name="seo_keywords" class="form-control" rows="2">{{ old('seo_keywords', $settings['seo_keywords']) }}</textarea>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="hero" role="tabpanel">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="page_title">Page Title</label>
                                    <input type="text" id="page_title" name="page_title" class="form-control" value="{{ old('page_title', $page->title) }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="meta_title">Meta Title</label>
                                    <input type="text" id="meta_title" name="meta_title" class="form-control" value="{{ old('meta_title', $page->meta_title) }}">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="meta_description">Meta Description</label>
                            <textarea id="meta_description" name="meta_description" class="form-control" rows="2">{{ old('meta_description', $page->meta_description) }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="hero_title">Hero Title</label>
                                    <input type="text" id="hero_title" name="hero_title" class="form-control" value="{{ old('hero_title', $page->hero_title) }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label" for="hero_title_highlight">Hero Highlight Word</label>
                                    <input type="text" id="hero_title_highlight" name="hero_title_highlight" class="form-control" value="{{ old('hero_title_highlight', $page->hero_title_highlight) }}">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="hero_subtitle">Hero Subtitle</label>
                            <input type="text" id="hero_subtitle" name="hero_subtitle" class="form-control" value="{{ old('hero_subtitle', $page->hero_subtitle) }}">
                        </div>
                        @if ($page->slug === 'home')
                            @php
                                $headerCta = $page->header_cta ?? [];
                                $heroButtons = $page->hero_buttons ?? [];
                                $heroPrimary = $heroButtons[0] ?? [];
                                $heroSecondary = $heroButtons[1] ?? [];
                            @endphp
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="header_button_label">Header Button Label</label>
                                        <input type="text" id="header_button_label" name="header_button_label" class="form-control" value="{{ old('header_button_label', $headerCta['label'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="header_button_url">Header Button URL</label>
                                        <input type="text" id="header_button_url" name="header_button_url" class="form-control" value="{{ old('header_button_url', $headerCta['url'] ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="hero_primary_label">Primary CTA Label</label>
                                        <input type="text" id="hero_primary_label" name="hero_primary_label" class="form-control" value="{{ old('hero_primary_label', $heroPrimary['label'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="hero_primary_url">Primary CTA URL</label>
                                        <input type="text" id="hero_primary_url" name="hero_primary_url" class="form-control" value="{{ old('hero_primary_url', $heroPrimary['url'] ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="hero_secondary_label">Secondary CTA Label</label>
                                        <input type="text" id="hero_secondary_label" name="hero_secondary_label" class="form-control" value="{{ old('hero_secondary_label', $heroSecondary['label'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="hero_secondary_url">Secondary CTA URL</label>
                                        <input type="text" id="hero_secondary_url" name="hero_secondary_url" class="form-control" value="{{ old('hero_secondary_url', $heroSecondary['url'] ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label" for="hero_image">Hero Image</label>
                            <input type="file" id="hero_image" name="hero_image" class="form-control" accept="image/*">
                            @if (!empty($page->hero_image_path))
                                <img src="{{ Storage::url($page->hero_image_path) }}" alt="Hero" class="img-fluid mt-2" style="max-height: 180px;">
                            @endif
                        </div>
                    </div>
                    @if ($page->slug === 'home')
                        @php
                            $categories = $page->categories ?? [];
                            $categoryItems = old('categories_items', $categories['items'] ?? []);
                            $vcard = $page->vcard_preview ?? [];
                            $how = $page->how_it_works ?? [];
                            $howSteps = old('how_steps', $how['steps'] ?? []);
                            $cta = $page->cta_section ?? [];
                            $footerLinks = $page->footer_links ?? [];
                            $productLinks = old('footer_product_links', $footerLinks['product'] ?? []);
                            $resourceLinks = old('footer_resource_links', $footerLinks['resources'] ?? []);
                        @endphp
                        <div class="tab-pane fade" id="categories" role="tabpanel">
                            <div class="mb-3">
                                <label class="form-label" for="categories_title">Categories Title</label>
                                <input type="text" id="categories_title" name="categories_title" class="form-control" value="{{ old('categories_title', $categories['title'] ?? '') }}">
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="categories_highlight">Title Highlight</label>
                                        <input type="text" id="categories_highlight" name="categories_highlight" class="form-control" value="{{ old('categories_highlight', $categories['highlight'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="categories_suffix">Title Suffix</label>
                                        <input type="text" id="categories_suffix" name="categories_suffix" class="form-control" value="{{ old('categories_suffix', $categories['suffix'] ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="categories_subtitle">Categories Subtitle</label>
                                <textarea id="categories_subtitle" name="categories_subtitle" class="form-control" rows="2">{{ old('categories_subtitle', $categories['subtitle'] ?? '') }}</textarea>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Categories</h6>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-add-item="categories">Add Category</button>
                            </div>
                            <div id="categories-list" data-collection>
                                @foreach ($categoryItems as $index => $item)
                                    <div class="border rounded-3 p-3 mb-3" data-item>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <strong><i class="{{ $item['icon'] ?? 'fas fa-building' }}"></i> {{ $item['title'] ?? 'Category' }}</strong>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-remove-item>Remove</button>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Title</label>
                                            <input type="text" class="form-control" name="categories_items[{{ $index }}][title]" value="{{ $item['title'] ?? '' }}">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Description</label>
                                            <input type="text" class="form-control" name="categories_items[{{ $index }}][description]" value="{{ $item['description'] ?? '' }}">
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Icon Class</label>
                                            <div class="d-flex align-items-center gap-2">
                                                <i id="icon-preview-{{ $index }}" class="{{ $item['icon'] ?? 'fas fa-building' }}" style="font-size: 24px; width: 40px; text-align: center;"></i>
                                                <select class="form-control icon-select-visual" name="categories_items[{{ $index }}][icon]" data-category-index="{{ $index }}" data-preview-id="icon-preview-{{ $index }}">
                                                    <option value="">-- Select Icon --</option>
                                                    @foreach ($themeIcons as $icon)
                                                        <option value="{{ $icon['value'] }}" data-icon="{{ $icon['icon'] }}" {{ ($item['icon'] ?? '') === $icon['value'] ? 'selected' : '' }}>
                                                            {{ $icon['name'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-lg-6">
                                                <label class="form-label">Icon Background</label>
                                                <input type="color" class="form-control form-control-color" name="categories_items[{{ $index }}][icon_bg_color]" value="{{ $item['icon_bg_color'] ?? ($themeColors['category_bg'] ?? '#dbeafe') }}" style="height: 40px;">
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="form-label">Icon Color</label>
                                                <input type="color" class="form-control form-control-color" name="categories_items[{{ $index }}][icon_color_value]" value="{{ $item['icon_color_value'] ?? ($themeColors['category_text'] ?? '#0369a1') }}" style="height: 40px;">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="tab-pane fade" id="vcard" role="tabpanel">
                            <div class="mb-3">
                                <label class="form-label" for="vcard_heading">Section Title</label>
                                <input type="text" id="vcard_heading" name="vcard_heading" class="form-control" value="{{ old('vcard_heading', $vcard['heading'] ?? '') }}">
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="vcard_highlight">Title Highlight</label>
                                        <input type="text" id="vcard_highlight" name="vcard_highlight" class="form-control" value="{{ old('vcard_highlight', $vcard['highlight'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="vcard_suffix">Title Suffix</label>
                                        <input type="text" id="vcard_suffix" name="vcard_suffix" class="form-control" value="{{ old('vcard_suffix', $vcard['suffix'] ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="vcard_subheading">Section Subtitle</label>
                                <textarea id="vcard_subheading" name="vcard_subheading" class="form-control" rows="2">{{ old('vcard_subheading', $vcard['subheading'] ?? '') }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label class="form-label" for="vcard_initials">Initials</label>
                                        <input type="text" id="vcard_initials" name="vcard_initials" class="form-control" value="{{ old('vcard_initials', $vcard['initials'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label class="form-label" for="vcard_name">Name</label>
                                        <input type="text" id="vcard_name" name="vcard_name" class="form-control" value="{{ old('vcard_name', $vcard['name'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="mb-3">
                                        <label class="form-label" for="vcard_role">Role</label>
                                        <input type="text" id="vcard_role" name="vcard_role" class="form-control" value="{{ old('vcard_role', $vcard['role'] ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="vcard_company">Company</label>
                                        <input type="text" id="vcard_company" name="vcard_company" class="form-control" value="{{ old('vcard_company', $vcard['company'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="vcard_location">Location</label>
                                        <input type="text" id="vcard_location" name="vcard_location" class="form-control" value="{{ old('vcard_location', $vcard['location'] ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="vcard_email">Email</label>
                                        <input type="text" id="vcard_email" name="vcard_email" class="form-control" value="{{ old('vcard_email', $vcard['email'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="vcard_phone">Phone</label>
                                        <input type="text" id="vcard_phone" name="vcard_phone" class="form-control" value="{{ old('vcard_phone', $vcard['phone'] ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="vcard_linkedin_label">LinkedIn Label</label>
                                        <input type="text" id="vcard_linkedin_label" name="vcard_linkedin_label" class="form-control" value="{{ old('vcard_linkedin_label', $vcard['linkedin_label'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="vcard_linkedin_url">LinkedIn URL</label>
                                        <input type="text" id="vcard_linkedin_url" name="vcard_linkedin_url" class="form-control" value="{{ old('vcard_linkedin_url', $vcard['linkedin_url'] ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="vcard_dribbble_label">Dribbble Label</label>
                                        <input type="text" id="vcard_dribbble_label" name="vcard_dribbble_label" class="form-control" value="{{ old('vcard_dribbble_label', $vcard['dribbble_label'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="vcard_dribbble_url">Dribbble URL</label>
                                        <input type="text" id="vcard_dribbble_url" name="vcard_dribbble_url" class="form-control" value="{{ old('vcard_dribbble_url', $vcard['dribbble_url'] ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="how" role="tabpanel">
                            <div class="mb-3">
                                <label class="form-label" for="how_title">Section Title</label>
                                <input type="text" id="how_title" name="how_title" class="form-control" value="{{ old('how_title', $how['title'] ?? '') }}">
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="how_highlight">Title Highlight</label>
                                        <input type="text" id="how_highlight" name="how_highlight" class="form-control" value="{{ old('how_highlight', $how['highlight'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="how_suffix">Title Suffix</label>
                                        <input type="text" id="how_suffix" name="how_suffix" class="form-control" value="{{ old('how_suffix', $how['suffix'] ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="how_subtitle">Section Subtitle</label>
                                <textarea id="how_subtitle" name="how_subtitle" class="form-control" rows="2">{{ old('how_subtitle', $how['subtitle'] ?? '') }}</textarea>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Steps</h6>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-add-item="how_steps">Add Step</button>
                            </div>
                            <div id="how_steps-list" data-collection>
                                @foreach ($howSteps as $index => $step)
                                    <div class="border rounded-3 p-3 mb-3" data-item>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <strong>Step {{ $step['number'] ?? ($index + 1) }} - {{ $step['title'] ?? 'New Step' }}</strong>
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-remove-item>Remove</button>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-2">
                                                <label class="form-label">Number</label>
                                                <input type="text" class="form-control" name="how_steps[{{ $index }}][number]" value="{{ $step['number'] ?? ($index + 1) }}">
                                            </div>
                                            <div class="col-lg-5">
                                                <label class="form-label">Title</label>
                                                <input type="text" class="form-control" name="how_steps[{{ $index }}][title]" value="{{ $step['title'] ?? '' }}">
                                            </div>
                                            <div class="col-lg-5">
                                                <label class="form-label">Description</label>
                                                <input type="text" class="form-control" name="how_steps[{{ $index }}][description]" value="{{ $step['description'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-lg-6">
                                                <label class="form-label">Badge Background</label>
                                                <input type="text" class="form-control" name="how_steps[{{ $index }}][badge_bg]" value="{{ $step['badge_bg'] ?? '' }}" placeholder="bg-blue-100">
                                            </div>
                                            <div class="col-lg-6">
                                                <label class="form-label">Badge Text Color</label>
                                                <input type="text" class="form-control" name="how_steps[{{ $index }}][badge_text]" value="{{ $step['badge_text'] ?? '' }}" placeholder="text-blue-700">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="tab-pane fade" id="cta" role="tabpanel">
                            <div class="mb-3">
                                <label class="form-label" for="cta_title">CTA Title</label>
                                <input type="text" id="cta_title" name="cta_title" class="form-control" value="{{ old('cta_title', $cta['title'] ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="cta_subtitle">CTA Subtitle</label>
                                <textarea id="cta_subtitle" name="cta_subtitle" class="form-control" rows="2">{{ old('cta_subtitle', $cta['subtitle'] ?? '') }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="cta_primary_label">Primary Button Label</label>
                                        <input type="text" id="cta_primary_label" name="cta_primary_label" class="form-control" value="{{ old('cta_primary_label', $cta['primary_label'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="cta_primary_url">Primary Button URL</label>
                                        <input type="text" id="cta_primary_url" name="cta_primary_url" class="form-control" value="{{ old('cta_primary_url', $cta['primary_url'] ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="cta_secondary_label">Secondary Button Label</label>
                                        <input type="text" id="cta_secondary_label" name="cta_secondary_label" class="form-control" value="{{ old('cta_secondary_label', $cta['secondary_label'] ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="cta_secondary_url">Secondary Button URL</label>
                                        <input type="text" id="cta_secondary_url" name="cta_secondary_url" class="form-control" value="{{ old('cta_secondary_url', $cta['secondary_url'] ?? '') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="footer" role="tabpanel">
                        <div class="mb-3">
                            <label class="form-label" for="footer_about">Footer About Text</label>
                            <textarea id="footer_about" name="footer_about" class="form-control" rows="3">{{ old('footer_about', $page->footer_about) }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Product Links</h6>
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-add-item="footer_product_links">Add Link</button>
                                </div>
                                <div id="footer_product_links-list" data-collection>
                                    @foreach ($productLinks as $index => $link)
                                        <div class="row g-2 mb-2" data-item>
                                            <div class="col-5">
                                                <input type="text" class="form-control" name="footer_product_links[{{ $index }}][label]" value="{{ $link['label'] ?? '' }}" placeholder="Label">
                                            </div>
                                            <div class="col-5">
                                                <input type="text" class="form-control" name="footer_product_links[{{ $index }}][url]" value="{{ $link['url'] ?? '' }}" placeholder="URL">
                                            </div>
                                            <div class="col-2">
                                                <button type="button" class="btn btn-sm btn-outline-danger w-100" data-remove-item>Remove</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Resources Links</h6>
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-add-item="footer_resource_links">Add Link</button>
                                </div>
                                <div id="footer_resource_links-list" data-collection>
                                    @foreach ($resourceLinks as $index => $link)
                                        <div class="row g-2 mb-2" data-item>
                                            <div class="col-5">
                                                <input type="text" class="form-control" name="footer_resource_links[{{ $index }}][label]" value="{{ $link['label'] ?? '' }}" placeholder="Label">
                                            </div>
                                            <div class="col-5">
                                                <input type="text" class="form-control" name="footer_resource_links[{{ $index }}][url]" value="{{ $link['url'] ?? '' }}" placeholder="URL">
                                            </div>
                                            <div class="col-2">
                                                <button type="button" class="btn btn-sm btn-outline-danger w-100" data-remove-item>Remove</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div style="padding: 20px;">
                    <button class="btn btn-primary" type="submit">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const themeIcons = @json($themeIcons ?? []);
        const themeColors = @json($themeColors ?? []);

        const socialPlatforms = [
            { name: 'Facebook', icon: 'fab fa-facebook', key: 'facebook' },
            { name: 'Instagram', icon: 'fab fa-instagram', key: 'instagram' },
            { name: 'Twitter / X', icon: 'fab fa-x-twitter', key: 'twitter' },
            { name: 'LinkedIn', icon: 'fab fa-linkedin', key: 'linkedin' },
            { name: 'YouTube', icon: 'fab fa-youtube', key: 'youtube' },
            { name: 'TikTok', icon: 'fab fa-tiktok', key: 'tiktok' },
            { name: 'GitHub', icon: 'fab fa-github', key: 'github' },
            { name: 'Dribbble', icon: 'fab fa-dribbble', key: 'dribbble' },
        ];

        const collections = {
            services: {
                fields: ['title', 'description']
            },
            testimonials: {
                fields: ['name', 'role', 'quote']
            },
            faqs: {
                fields: ['question', 'answer']
            }
        };

        // Create searchable icon select
        function createIconSelect(index, currentValue = '') {
            const wrapper = document.createElement('div');
            wrapper.className = 'icon-select-wrapper position-relative';
            wrapper.style.marginBottom = '12px';

            const label = document.createElement('label');
            label.className = 'form-label';
            label.textContent = 'Icon (Search or Select)';
            wrapper.appendChild(label);

            const searchInput = document.createElement('input');
            searchInput.type = 'text';
            searchInput.className = 'form-control mb-2';
            searchInput.placeholder = 'Search icons... (e.g., building, user, chart)';
            searchInput.setAttribute('data-icon-search', index);
            searchInput.style.borderColor = '#0d6efd';
            wrapper.appendChild(searchInput);

            const dropdown = document.createElement('div');
            dropdown.className = 'border rounded shadow-sm';
            dropdown.setAttribute('data-icon-dropdown', index);
            dropdown.style.display = 'none';
            dropdown.style.zIndex = '1000';
            dropdown.style.maxHeight = '400px';
            dropdown.style.overflowY = 'auto';
            dropdown.style.position = 'absolute';
            dropdown.style.width = '100%';
            dropdown.style.left = '0';
            dropdown.style.right = '0';
            dropdown.style.backgroundColor = '#fff';
            dropdown.style.marginTop = '-8px';

            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = `categories_items[${index}][icon]`;
            hiddenInput.value = currentValue || 'fas fa-building';

            // Create icon options with data attributes
            const iconOptions = themeIcons.map(ic => {
                const optDiv = document.createElement('div');
                optDiv.className = 'p-3 cursor-pointer d-flex align-items-center border-bottom';
                optDiv.style.cursor = 'pointer';
                optDiv.style.transition = 'background-color 0.2s';
                optDiv.setAttribute('data-icon-value', ic.value);
                optDiv.setAttribute('data-icon-name', ic.name.toLowerCase());
                optDiv.setAttribute('data-icon-class', ic.icon);
                optDiv.innerHTML = `<i class="${ic.icon}" style="width: 28px; margin-right: 12px; font-size: 18px;"></i> <span class="flex-grow-1">${ic.name}</span>`;
                
                optDiv.addEventListener('mouseover', () => {
                    optDiv.style.backgroundColor = '#f0f0f0';
                });
                optDiv.addEventListener('mouseout', () => {
                    optDiv.style.backgroundColor = 'transparent';
                });
                optDiv.addEventListener('click', () => {
                    hiddenInput.value = ic.value;
                    searchInput.value = ic.name;
                    dropdown.style.display = 'none';
                    searchInput.focus();
                });
                return optDiv;
            });

            iconOptions.forEach(opt => dropdown.appendChild(opt));

            // Search functionality with data attributes
            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase().trim();
                let visibleCount = 0;
                
                Array.from(dropdown.children).forEach(opt => {
                    const name = opt.getAttribute('data-icon-name');
                    const match = !query || name.includes(query);
                    opt.style.display = match ? 'flex' : 'none';
                    if (match) visibleCount++;
                });
                
                dropdown.style.display = visibleCount > 0 ? 'block' : 'none';
            });

            searchInput.addEventListener('focus', () => {
                dropdown.style.display = 'block';
            });

            searchInput.addEventListener('blur', () => {
                setTimeout(() => { 
                    dropdown.style.display = 'none'; 
                }, 200);
            });

            // Keyboard navigation
            searchInput.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                    e.preventDefault();
                    const visibleOptions = Array.from(dropdown.children).filter(opt => opt.style.display !== 'none');
                    if (visibleOptions.length > 0) {
                        visibleOptions[0].click();
                    }
                } else if (e.key === 'Escape') {
                    dropdown.style.display = 'none';
                }
            });

            wrapper.appendChild(searchInput);
            wrapper.appendChild(hiddenInput);
            wrapper.appendChild(dropdown);
            return wrapper;
        }

        function createItem(collection, index) {
            if (collection === 'social-links') {
                const container = document.createElement('div');
                container.className = 'border rounded-3 p-3 mb-3';
                container.setAttribute('data-item', '');

                const headerDiv = document.createElement('div');
                headerDiv.className = 'd-flex justify-content-between align-items-center mb-2';

                const titleSpan = document.createElement('strong');
                titleSpan.id = `social-title-${index}`;
                titleSpan.innerHTML = '<i class="fab fa-facebook"></i> Facebook';

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'btn btn-sm btn-outline-danger';
                removeBtn.textContent = 'Remove';
                removeBtn.setAttribute('data-remove-item', '');

                headerDiv.appendChild(titleSpan);
                headerDiv.appendChild(removeBtn);

                const selectDiv = document.createElement('div');
                selectDiv.className = 'mb-3';

                const select = document.createElement('select');
                select.className = 'form-control mb-2';
                select.setAttribute('data-social-select', index);
                select.innerHTML = '<option value="">-- Select Social Platform --</option>' + 
                    socialPlatforms.map(p => `<option value="${p.key}">${p.name}</option>`).join('');

                select.addEventListener('change', (e) => {
                    const selected = socialPlatforms.find(p => p.key === e.target.value);
                    if (selected) {
                        document.querySelector(`input[name="social_links[${index}][key]"]`).value = selected.key;
                        document.querySelector(`input[name="social_links[${index}][name]"]`).value = selected.name;
                        document.querySelector(`input[name="social_links[${index}][icon]"]`).value = selected.icon;
                        document.getElementById(`social-title-${index}`).innerHTML = `<i class="${selected.icon}"></i> ${selected.name}`;
                    }
                });

                selectDiv.appendChild(select);

                const urlDiv = document.createElement('div');
                urlDiv.className = 'mb-2';
                const urlInput = document.createElement('input');
                urlInput.type = 'text';
                urlInput.className = 'form-control';
                urlInput.name = `social_links[${index}][url]`;
                urlInput.placeholder = 'https://example.com';
                urlDiv.appendChild(urlInput);

                const keyInput = document.createElement('input');
                keyInput.type = 'hidden';
                keyInput.name = `social_links[${index}][key]`;
                keyInput.value = 'facebook';

                const nameInput = document.createElement('input');
                nameInput.type = 'hidden';
                nameInput.name = `social_links[${index}][name]`;
                nameInput.value = 'Facebook';

                const iconInput = document.createElement('input');
                iconInput.type = 'hidden';
                iconInput.name = `social_links[${index}][icon]`;
                iconInput.value = 'fab fa-facebook';

                container.appendChild(headerDiv);
                container.appendChild(selectDiv);
                container.appendChild(urlDiv);
                container.appendChild(keyInput);
                container.appendChild(nameInput);
                container.appendChild(iconInput);

                return container;
            }

            if (collection === 'categories') {
                const container = document.createElement('div');
                container.className = 'border rounded-3 p-3 mb-3';
                container.setAttribute('data-item', '');

                const headerDiv = document.createElement('div');
                headerDiv.className = 'd-flex justify-content-between align-items-center mb-2';

                const titleSpan = document.createElement('strong');
                titleSpan.id = `cat-title-${index}`;
                titleSpan.innerHTML = '<i class="fas fa-building"></i> Category';

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'btn btn-sm btn-outline-danger';
                removeBtn.textContent = 'Remove';
                removeBtn.setAttribute('data-remove-item', '');

                headerDiv.appendChild(titleSpan);
                headerDiv.appendChild(removeBtn);

                const titleInput = document.createElement('input');
                titleInput.type = 'text';
                titleInput.className = 'form-control mb-2';
                titleInput.name = `categories_items[${index}][title]`;
                titleInput.placeholder = 'Category Title';
                titleInput.addEventListener('input', () => {
                    const iconVal = container.querySelector('select[name*="[icon]"]')?.value || 'fas fa-building';
                    const selectedOption = container.querySelector('select[name*="[icon]"] option:checked');
                    const iconClass = selectedOption?.getAttribute('data-icon') || 'fas fa-building';
                    titleSpan.innerHTML = `<i class="${iconClass}"></i> ${titleInput.value || 'Category'}`;
                });

                const descInput = document.createElement('input');
                descInput.type = 'text';
                descInput.className = 'form-control mb-2';
                descInput.name = `categories_items[${index}][description]`;
                descInput.placeholder = 'Category Description';

                // Icon select wrapper with preview
                const iconWrapper = document.createElement('div');
                iconWrapper.className = 'mb-2';
                const iconLabel = document.createElement('label');
                iconLabel.className = 'form-label';
                iconLabel.textContent = 'Icon Class';
                
                const iconDisplayDiv = document.createElement('div');
                iconDisplayDiv.className = 'd-flex align-items-center gap-2';
                
                const iconPreview = document.createElement('i');
                iconPreview.id = `icon-preview-new-${index}`;
                iconPreview.className = 'fas fa-building';
                iconPreview.style.fontSize = '24px';
                iconPreview.style.width = '40px';
                iconPreview.style.textAlign = 'center';
                
                const iconSelect = document.createElement('select');
                iconSelect.className = 'form-control';
                iconSelect.name = `categories_items[${index}][icon]`;
                iconSelect.innerHTML = '<option value="">-- Select Icon --</option>' +
                    themeIcons.map(ic => `<option value="${ic.value}" data-icon="${ic.icon}">${ic.name}</option>`).join('');
                
                iconSelect.addEventListener('change', (e) => {
                    const selectedOption = e.target.options[e.target.selectedIndex];
                    const iconClass = selectedOption.getAttribute('data-icon') || 'fas fa-building';
                    iconPreview.className = iconClass;
                    iconPreview.style.fontSize = '24px';
                    iconPreview.style.width = '40px';
                    iconPreview.style.textAlign = 'center';
                    titleSpan.innerHTML = `<i class="${iconClass}"></i> ${titleInput.value || 'Category'}`;
                });
                
                iconDisplayDiv.appendChild(iconPreview);
                iconDisplayDiv.appendChild(iconSelect);
                iconWrapper.appendChild(iconLabel);
                iconWrapper.appendChild(iconDisplayDiv);

                const bgColorInput = document.createElement('input');
                bgColorInput.type = 'color';
                bgColorInput.className = 'form-control form-control-color';
                bgColorInput.name = `categories_items[${index}][icon_bg_color]`;
                bgColorInput.value = themeColors?.category_bg || '#dbeafe';
                bgColorInput.style.height = '40px';

                const textColorInput = document.createElement('input');
                textColorInput.type = 'color';
                textColorInput.className = 'form-control form-control-color';
                textColorInput.name = `categories_items[${index}][icon_color_value]`;
                textColorInput.value = themeColors?.category_text || '#0369a1';
                textColorInput.style.height = '40px';

                const colorsDiv = document.createElement('div');
                colorsDiv.className = 'row mt-2';
                
                const bgCol = document.createElement('div');
                bgCol.className = 'col-lg-6';
                const bgLabel = document.createElement('label');
                bgLabel.className = 'form-label';
                bgLabel.textContent = 'Icon Background';
                bgCol.appendChild(bgLabel);
                bgCol.appendChild(bgColorInput);
                colorsDiv.appendChild(bgCol);

                const textCol = document.createElement('div');
                textCol.className = 'col-lg-6';
                const textLabel = document.createElement('label');
                textLabel.className = 'form-label';
                textLabel.textContent = 'Icon Color';
                textCol.appendChild(textLabel);
                textCol.appendChild(textColorInput);
                colorsDiv.appendChild(textCol);

                container.appendChild(headerDiv);
                container.appendChild(titleInput);
                container.appendChild(descInput);
                container.appendChild(iconWrapper);
                container.appendChild(colorsDiv);

                return container;
            }

            if (collection === 'how_steps') {
                const container = document.createElement('div');
                container.className = 'border rounded-3 p-3 mb-3';
                container.setAttribute('data-item', '');

                const headerDiv = document.createElement('div');
                headerDiv.className = 'd-flex justify-content-between align-items-center mb-2';

                const titleSpan = document.createElement('strong');
                titleSpan.id = `step-title-${index}`;
                titleSpan.textContent = `Step ${index + 1} - New Step`;

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'btn btn-sm btn-outline-danger';
                removeBtn.textContent = 'Remove';
                removeBtn.setAttribute('data-remove-item', '');

                headerDiv.appendChild(titleSpan);
                headerDiv.appendChild(removeBtn);

                const rowDiv = document.createElement('div');
                rowDiv.className = 'row';

                const numberCol = document.createElement('div');
                numberCol.className = 'col-lg-2';
                const numberLabel = document.createElement('label');
                numberLabel.className = 'form-label';
                numberLabel.textContent = 'Number';
                const numberInput = document.createElement('input');
                numberInput.type = 'text';
                numberInput.className = 'form-control';
                numberInput.name = `how_steps[${index}][number]`;
                numberInput.value = index + 1;
                numberInput.addEventListener('input', () => {
                    titleSpan.textContent = `Step ${numberInput.value} - ${titleInput.value || 'New Step'}`;
                });
                numberCol.appendChild(numberLabel);
                numberCol.appendChild(numberInput);
                rowDiv.appendChild(numberCol);

                const titleCol = document.createElement('div');
                titleCol.className = 'col-lg-5';
                const titleLabel = document.createElement('label');
                titleLabel.className = 'form-label';
                titleLabel.textContent = 'Title';
                const titleInput = document.createElement('input');
                titleInput.type = 'text';
                titleInput.className = 'form-control';
                titleInput.name = `how_steps[${index}][title]`;
                titleInput.placeholder = 'Step Title';
                titleInput.addEventListener('input', () => {
                    titleSpan.textContent = `Step ${numberInput.value} - ${titleInput.value || 'New Step'}`;
                });
                titleCol.appendChild(titleLabel);
                titleCol.appendChild(titleInput);
                rowDiv.appendChild(titleCol);

                const descCol = document.createElement('div');
                descCol.className = 'col-lg-5';
                const descLabel = document.createElement('label');
                descLabel.className = 'form-label';
                descLabel.textContent = 'Description';
                const descInput = document.createElement('input');
                descInput.type = 'text';
                descInput.className = 'form-control';
                descInput.name = `how_steps[${index}][description]`;
                descInput.placeholder = 'Step Description';
                descCol.appendChild(descLabel);
                descCol.appendChild(descInput);
                rowDiv.appendChild(descCol);

                const colorsRowDiv = document.createElement('div');
                colorsRowDiv.className = 'row mt-2';

                const bgCol = document.createElement('div');
                bgCol.className = 'col-lg-6';
                const bgLabel = document.createElement('label');
                bgLabel.className = 'form-label';
                bgLabel.textContent = 'Badge Background';
                const bgInput = document.createElement('input');
                bgInput.type = 'text';
                bgInput.className = 'form-control';
                bgInput.name = `how_steps[${index}][badge_bg]`;
                bgInput.placeholder = 'bg-blue-100';
                bgCol.appendChild(bgLabel);
                bgCol.appendChild(bgInput);
                colorsRowDiv.appendChild(bgCol);

                const textCol = document.createElement('div');
                textCol.className = 'col-lg-6';
                const textLabel = document.createElement('label');
                textLabel.className = 'form-label';
                textLabel.textContent = 'Badge Text Color';
                const textInput = document.createElement('input');
                textInput.type = 'text';
                textInput.className = 'form-control';
                textInput.name = `how_steps[${index}][badge_text]`;
                textInput.placeholder = 'text-blue-700';
                textCol.appendChild(textLabel);
                textCol.appendChild(textInput);
                colorsRowDiv.appendChild(textCol);

                container.appendChild(headerDiv);
                container.appendChild(rowDiv);
                container.appendChild(colorsRowDiv);

                return container;
            }

            if (collection === 'footer_product_links' || collection === 'footer_resource_links') {
                const rowDiv = document.createElement('div');
                rowDiv.className = 'row g-2 mb-2';
                rowDiv.setAttribute('data-item', '');

                const labelCol = document.createElement('div');
                labelCol.className = 'col-5';
                const labelInput = document.createElement('input');
                labelInput.type = 'text';
                labelInput.className = 'form-control';
                labelInput.name = `${collection}[${index}][label]`;
                labelInput.placeholder = 'Label';
                labelCol.appendChild(labelInput);
                rowDiv.appendChild(labelCol);

                const urlCol = document.createElement('div');
                urlCol.className = 'col-5';
                const urlInput = document.createElement('input');
                urlInput.type = 'text';
                urlInput.className = 'form-control';
                urlInput.name = `${collection}[${index}][url]`;
                urlInput.placeholder = 'URL';
                urlCol.appendChild(urlInput);
                rowDiv.appendChild(urlCol);

                const btnCol = document.createElement('div');
                btnCol.className = 'col-2';
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.className = 'btn btn-sm btn-outline-danger w-100';
                removeBtn.textContent = 'Remove';
                removeBtn.setAttribute('data-remove-item', '');
                btnCol.appendChild(removeBtn);
                rowDiv.appendChild(btnCol);

                return rowDiv;
            }
        }

        document.querySelectorAll('[data-add-item]').forEach((button) => {
            button.addEventListener('click', () => {
                const collection = button.getAttribute('data-add-item');
                const list = document.getElementById(`${collection}-list`);
                
                // Get all existing items with data-item and calculate next index
                const items = list.querySelectorAll('[data-item]');
                let maxIndex = -1;
                
                items.forEach((item) => {
                    // Extract index from any form field name like categories_items[5][anything] or how_steps[3][anything]
                    const anyInput = item.querySelector('input[name*="["]');
                    if (anyInput) {
                        const match = anyInput.name.match(/\[(\d+)\]/);
                        if (match) {
                            const idx = parseInt(match[1]);
                            if (idx > maxIndex) maxIndex = idx;
                        }
                    }
                });
                
                const nextIndex = maxIndex + 1;
                list.appendChild(createItem(collection, nextIndex));
                
                // Scroll new item into view
                const allItems = list.querySelectorAll('[data-item]');
                const newItem = allItems[allItems.length - 1];
                if (newItem) {
                    setTimeout(() => {
                        newItem.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }, 100);
                }
            });
        });

        document.addEventListener('click', (event) => {
            if (event.target.matches('[data-remove-item]')) {
                const item = event.target.closest('[data-item]');
                if (item) {
                    item.remove();
                }
            }
        });

        // Handle icon preview updates for existing categories
        document.querySelectorAll('.icon-select-visual').forEach((select) => {
            const previewId = select.getAttribute('data-preview-id');
            const preview = document.getElementById(previewId);
            
            select.addEventListener('change', (e) => {
                const selectedOption = e.target.options[e.target.selectedIndex];
                const iconClass = selectedOption.getAttribute('data-icon');
                if (preview && iconClass) {
                    preview.className = iconClass;
                    preview.style.fontSize = '24px';
                    preview.style.width = '40px';
                    preview.style.textAlign = 'center';
                }
            });
        });
    </script>
@endpush

