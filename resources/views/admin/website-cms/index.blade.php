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
        <div class="alert alert-success">Website settings updated successfully!</div>
    @endif

    <div class="row g-4">
        <!-- General Settings -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 hover-shadow cursor-pointer" onclick="window.location.href='{{ route('admin.website-cms.general', $page) }}'">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-cog fs-1 text-primary"></i>
                    </div>
                    <h5 class="card-title">General Settings</h5>
                    <p class="card-text text-muted">Site name, tagline, contact info</p>
                </div>
            </div>
        </div>

        <!-- Branding -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 hover-shadow cursor-pointer" onclick="window.location.href='{{ route('admin.website-cms.branding', $page) }}'">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-palette fs-1 text-success"></i>
                    </div>
                    <h5 class="card-title">Branding</h5>
                    <p class="card-text text-muted">Logo, colors, favicon</p>
                </div>
            </div>
        </div>

        <!-- Social Links -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 hover-shadow cursor-pointer" onclick="window.location.href='{{ route('admin.website-cms.social', $page) }}'">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-share-alt fs-1 text-info"></i>
                    </div>
                    <h5 class="card-title">Social Links</h5>
                    <p class="card-text text-muted">Social media profiles</p>
                </div>
            </div>
        </div>

        <!-- SEO Settings -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 hover-shadow cursor-pointer" onclick="window.location.href='{{ route('admin.website-cms.seo', $page) }}'">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-search fs-1 text-warning"></i>
                    </div>
                    <h5 class="card-title">SEO Settings</h5>
                    <p class="card-text text-muted">Meta tags and keywords</p>
                </div>
            </div>
        </div>

        <!-- Hero Section -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 hover-shadow cursor-pointer" onclick="window.location.href='{{ route('admin.website-cms.hero', $page) }}'">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-image fs-1 text-danger"></i>
                    </div>
                    <h5 class="card-title">Hero Section</h5>
                    <p class="card-text text-muted">Hero title, subtitle, buttons</p>
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 hover-shadow cursor-pointer" onclick="window.location.href='{{ route('admin.website-cms.categories', $page) }}'">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-th fs-1 text-primary"></i>
                    </div>
                    <h5 class="card-title">Categories</h5>
                    <p class="card-text text-muted">Manage category items</p>
                </div>
            </div>
        </div>

        <!-- How It Works -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 hover-shadow cursor-pointer" onclick="window.location.href='{{ route('admin.website-cms.how-it-works', $page) }}'">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-list-ol fs-1 text-success"></i>
                    </div>
                    <h5 class="card-title">How It Works</h5>
                    <p class="card-text text-muted">Steps and process</p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 hover-shadow cursor-pointer" onclick="window.location.href='{{ route('admin.website-cms.cta', $page) }}'">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-bullhorn fs-1 text-warning"></i>
                    </div>
                    <h5 class="card-title">CTA Section</h5>
                    <p class="card-text text-muted">Call-to-action content</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 hover-shadow cursor-pointer" onclick="window.location.href='{{ route('admin.website-cms.footer', $page) }}'">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-link fs-1 text-danger"></i>
                    </div>
                    <h5 class="card-title">Footer</h5>
                    <p class="card-text text-muted">Footer content and links</p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-shadow {
            transition: all 0.3s ease;
        }
        .hover-shadow:hover {
            box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
            transform: translateY(-2px);
        }
        .cursor-pointer {
            cursor: pointer;
        }
    </style>
@endsection
