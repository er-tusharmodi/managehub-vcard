<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>{{ $page->meta_title ?: $page->title }} | {{ $settings['site_name'] ?? 'ManageHub' }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="{{ $page->meta_description ?: ($settings['site_tagline'] ?? '') }}" />

        @if (!empty($settings['favicon_path']))
            <link rel="shortcut icon" href="{{ Storage::url($settings['favicon_path']) }}">
        @else
            <link rel="shortcut icon" href="{{ asset('backendtheme/assets/images/favicon.ico') }}">
        @endif

        <link href="{{ asset('backendtheme/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('backendtheme/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />

        @stack('styles')
    </head>

    <body data-menu-color="light" data-sidebar="default">
        <div id="app-layout">
            <div class="topbar-custom">
                <div class="container-xxl">
                    <div class="d-flex justify-content-between">
                        <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                            <li>
                                <button class="button-toggle-menu nav-link ps-0">
                                    <i data-feather="menu" class="noti-icon"></i>
                                </button>
                            </li>
                            <li class="d-none d-lg-block">
                                <div class="position-relative topbar-search">
                                    <input type="text" class="form-control bg-light bg-opacity-75 border-light ps-4" placeholder="Search...">
                                    <i class="mdi mdi-magnify fs-16 position-absolute text-muted top-50 translate-middle-y ms-2"></i>
                                </div>
                            </li>
                        </ul>

                        <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                            <li class="d-none d-sm-flex">
                                <button type="button" class="btn nav-link" data-toggle="fullscreen">
                                    <i data-feather="maximize" class="align-middle fullscreen noti-icon"></i>
                                </button>
                            </li>
                            <li>
                                <a href="{{ route('admin.login') }}" class="btn btn-sm btn-primary">Admin Login</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="app-sidebar-menu">
                <div class="h-100" data-simplebar>
                    <div id="sidebar-menu">
                        <div class="logo-box">
                            <a href="{{ url('/') }}" class="logo logo-light">
                                <span class="logo-sm">
                                    @if (!empty($settings['logo_path']))
                                        <img src="{{ Storage::url($settings['logo_path']) }}" alt="" height="22">
                                    @else
                                        <img src="{{ asset('backendtheme/assets/images/logo-sm.png') }}" alt="" height="22">
                                    @endif
                                </span>
                                <span class="logo-lg">
                                    @if (!empty($settings['logo_path']))
                                        <img src="{{ Storage::url($settings['logo_path']) }}" alt="" height="24">
                                    @else
                                        <img src="{{ asset('backendtheme/assets/images/logo-light.png') }}" alt="" height="24">
                                    @endif
                                </span>
                            </a>
                            <a href="{{ url('/') }}" class="logo logo-dark">
                                <span class="logo-sm">
                                    @if (!empty($settings['logo_path']))
                                        <img src="{{ Storage::url($settings['logo_path']) }}" alt="" height="22">
                                    @else
                                        <img src="{{ asset('backendtheme/assets/images/logo-sm.png') }}" alt="" height="22">
                                    @endif
                                </span>
                                <span class="logo-lg">
                                    @if (!empty($settings['logo_path']))
                                        <img src="{{ Storage::url($settings['logo_path']) }}" alt="" height="24">
                                    @else
                                        <img src="{{ asset('backendtheme/assets/images/logo-dark.png') }}" alt="" height="24">
                                    @endif
                                </span>
                            </a>
                        </div>

                        <ul id="side-menu">
                            <li class="menu-title">Pages</li>
                            @foreach ($pages as $navPage)
                                <li>
                                    <a href="{{ $navPage->slug === 'home' ? url('/') : url('/' . $navPage->slug) }}" class="tp-link {{ $page->id === $navPage->id ? 'active' : '' }}">
                                        <i data-feather="file-text"></i>
                                        <span>{{ $navPage->title }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="content-page">
                <div class="content">
                    @yield('content')
                </div>

                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col fs-13 text-muted text-center">
                                &copy; <script>document.write(new Date().getFullYear())</script> {{ $settings['site_name'] ?? 'ManageHub' }}
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <script src="{{ asset('backendtheme/assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('backendtheme/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('backendtheme/assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('backendtheme/assets/libs/node-waves/waves.min.js') }}"></script>
        <script src="{{ asset('backendtheme/assets/libs/waypoints/lib/jquery.waypoints.min.js') }}"></script>
        <script src="{{ asset('backendtheme/assets/libs/jquery.counterup/jquery.counterup.min.js') }}"></script>
        <script src="{{ asset('backendtheme/assets/libs/feather-icons/feather.min.js') }}"></script>
        <script src="{{ asset('backendtheme/assets/js/app.js') }}"></script>
        @stack('scripts')
    </body>
</html>
