<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>@yield('title', 'Admin') | ManageHub</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="ManageHub admin panel" />
        <meta name="author" content="ManageHub" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <link rel="shortcut icon" href="{{ \App\Helpers\BrandingHelper::getFaviconUrl() }}">

        <link href="{{ asset('backendtheme/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
        <link href="{{ asset('backendtheme/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" type="text/css" />

        <style>
            .toast-container {
                position: static;
            }
        </style>

        @livewireStyles
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

                            <li class="dropdown notification-list topbar-dropdown">
                                <a class="nav-link dropdown-toggle nav-user me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                    <img src="{{ auth()->user()?->profile_photo_path ? Storage::url(auth()->user()->profile_photo_path) : asset('backendtheme/assets/images/users/user-11.jpg') }}" alt="user-image" class="rounded-circle">
                                    <span class="pro-user-name ms-1">
                                        {{ auth()->user()->name ?? 'Admin' }} <i class="mdi mdi-chevron-down"></i>
                                    </span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                                    <div class="dropdown-header noti-title">
                                        <h6 class="text-overflow m-0">Welcome!</h6>
                                    </div>

                                    <a href="{{ route('admin.profile.edit') }}" class="dropdown-item notify-item">
                                        <i class="mdi mdi-account-circle-outline fs-16 align-middle"></i>
                                        <span>My Account</span>
                                    </a>

                                    <div class="dropdown-divider"></div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item notify-item">
                                            <i class="mdi mdi-location-exit fs-16 align-middle"></i>
                                            <span>Logout</span>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="app-sidebar-menu">
                <div class="h-100" data-simplebar>
                    <div id="sidebar-menu">
                        <div class="logo-box">
                            <a href="{{ route('admin.dashboard') }}" class="logo logo-light">
                                <span class="logo-lg">
                                    <img src="{{ \App\Helpers\BrandingHelper::getLogoUrl() }}" alt="Logo" height="40" style="object-fit: contain; width:100%;">
                                </span>
                            </a>
                            <a href="{{ route('admin.dashboard') }}" class="logo logo-dark">
                                <span class="logo-lg">
                                    <img src="{{ \App\Helpers\BrandingHelper::getLogoUrl() }}" alt="Logo" height="40" style="object-fit: contain; width:100%;">
                                </span>
                            </a>
                        </div>

                        <ul id="side-menu">
                            <li class="menu-title">Menu</li>

                            <li>
                                <a href="{{ route('admin.dashboard') }}" class="tp-link">
                                    <i data-feather="home"></i>
                                    <span> Dashboard </span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.admins.index') }}" class="tp-link">
                                    <i data-feather="users"></i>
                                    <span> Admins </span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.website-cms', 'home') }}" class="tp-link">
                                    <i data-feather="layout"></i>
                                    <span> Website CMS </span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.vcards.index') }}" class="tp-link">
                                    <i data-feather="credit-card"></i>
                                    <span> vCards </span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.clients.index') }}" class="tp-link">
                                    <i data-feather="users"></i>
                                    <span> Clients </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="content-page position-relative">
                <div class="content">
                    <div class="container-xxl">
                        @yield('content')
                    </div>
                </div>

                <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1090; max-width: calc(100vw - 24px);">
                    <div id="admin-toast-container" class="toast-container"></div>
                </div>

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

        <script>
            (function () {
                var toastListenerRegistered = false;
                var lastToastKey = null;
                var lastToastAt = 0;

                function toastClasses(type) {
                    switch (type) {
                        case 'success':
                            return 'text-bg-success';
                        case 'error':
                            return 'text-bg-danger';
                        case 'warning':
                            return 'text-bg-warning';
                        default:
                            return 'text-bg-info';
                    }
                }

                function showToast(type, message) {
                    var container = document.getElementById('admin-toast-container');
                    if (!container || !message) {
                        return;
                    }

                    var now = Date.now();
                    var toastKey = String(type) + '|' + String(message);
                    if (toastKey === lastToastKey && now - lastToastAt < 800) {
                        return;
                    }
                    lastToastKey = toastKey;
                    lastToastAt = now;

                    var toastEl = document.createElement('div');
                    toastEl.className = 'toast align-items-center ' + toastClasses(type) + ' border-0 mb-2 fade';
                    toastEl.setAttribute('role', 'alert');
                    toastEl.setAttribute('aria-live', 'assertive');
                    toastEl.setAttribute('aria-atomic', 'true');
                    toastEl.innerHTML = '' +
                        '<div class="d-flex">' +
                            '<div class="toast-body">' + message + '</div>' +
                            '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>' +
                        '</div>';

                    container.appendChild(toastEl);
                    if (window.bootstrap && typeof bootstrap.Toast === 'function') {
                        var toast = new bootstrap.Toast(toastEl, { delay: 4000 });
                        toast.show();
                        toastEl.addEventListener('hidden.bs.toast', function () {
                            toastEl.remove();
                        });
                    } else {
                        toastEl.classList.add('show');
                        setTimeout(function () {
                            toastEl.classList.remove('show');
                            toastEl.classList.add('hide');
                            toastEl.remove();
                        }, 4000);
                    }
                }

                function showConfirmToast(message, onConfirm) {
                    var container = document.getElementById('admin-toast-container');
                    if (!container || !message) {
                        return;
                    }

                    var toastEl = document.createElement('div');
                    toastEl.className = 'toast align-items-center text-bg-warning border-0 mb-2 fade';
                    toastEl.setAttribute('role', 'alert');
                    toastEl.setAttribute('aria-live', 'assertive');
                    toastEl.setAttribute('aria-atomic', 'true');
                    toastEl.innerHTML = '' +
                        '<div class="d-flex">' +
                            '<div class="toast-body">' + message + '</div>' +
                            '<div class="d-flex align-items-center gap-2 pe-2">' +
                                '<button type="button" class="btn btn-sm btn-danger text-white" data-confirm="true" style="background-color: #dc3545; border-color: #dc3545;">Delete</button>' +
                                '<button type="button" class="btn btn-sm btn-light" data-cancel="true">Cancel</button>' +
                            '</div>' +
                        '</div>';

                    container.appendChild(toastEl);

                    var confirmBtn = toastEl.querySelector('[data-confirm]');
                    var cancelBtn = toastEl.querySelector('[data-cancel]');

                    function cleanup() {
                        if (toastEl && toastEl.parentNode) {
                            toastEl.remove();
                        }
                    }

                    if (confirmBtn) {
                        confirmBtn.addEventListener('click', function () {
                            cleanup();
                            if (typeof onConfirm === 'function') {
                                onConfirm();
                            }
                        });
                    }

                    if (cancelBtn) {
                        cancelBtn.addEventListener('click', function () {
                            cleanup();
                        });
                    }

                    if (window.bootstrap && typeof bootstrap.Toast === 'function') {
                        var toast = new bootstrap.Toast(toastEl, { autohide: false });
                        toast.show();
                    } else {
                        toastEl.classList.add('show');
                    }
                }

                document.addEventListener('DOMContentLoaded', function () {
                    var successMessage = @json(session('success'));
                    var errorMessage = @json(session('error'));
                    if (successMessage) {
                        showToast('success', successMessage);
                    }
                    if (errorMessage) {
                        showToast('error', errorMessage);
                    }
                });

                function normalizePayload(args) {
                    if (!args || !args.length) {
                        return null;
                    }

                    var payload = args[0];
                    if (Array.isArray(payload)) {
                        if (payload.length === 1 && payload[0] && payload[0].message) {
                            return { type: payload[0].type || 'info', message: payload[0].message };
                        }
                        if (payload.length >= 2 && typeof payload[1] === 'string') {
                            return { type: payload[0] || 'info', message: payload[1] };
                        }
                    }
                    if (typeof payload === 'string') {
                        return { type: 'info', message: payload };
                    }

                    if (payload && payload.message) {
                        return { type: payload.type || 'info', message: payload.message };
                    }

                    if (args.length >= 2 && typeof args[1] === 'string') {
                        return { type: payload || 'info', message: args[1] };
                    }

                    if (payload && payload.detail && payload.detail.message) {
                        return { type: payload.detail.type || 'info', message: payload.detail.message };
                    }

                    return null;
                }

                function handleNotifyEvent(event) {
                    var normalized = normalizePayload([event.detail]);
                    if (!normalized) {
                        normalized = normalizePayload([event]);
                    }
                    if (normalized) {
                        showToast(normalized.type, normalized.message);
                    }
                }

                document.addEventListener('notify', handleNotifyEvent);
                window.addEventListener('notify', handleNotifyEvent);

                function handleConfirmDeleteEvent(event) {
                    var detail = (event && event.detail) ? event.detail : {};
                    var componentId = detail.id;
                    var index = detail.index;
                    var path = detail.path || '';
                    var message = detail.message || 'Delete this item?';
                    var method = detail.method || 'deleteVcardConfirmed';
                    if (!componentId || typeof index === 'undefined') {
                        return;
                    }

                    showConfirmToast(message, function () {
                        if (window.Livewire && Livewire.find) {
                            var component = Livewire.find(componentId);
                            if (component) {
                                // Call with path if it exists, otherwise just index
                                if (path !== '') {
                                    component.call(method, index, path);
                                } else {
                                    component.call(method, index);
                                }
                            }
                        }
                    });
                }

                document.addEventListener('confirm-delete', handleConfirmDeleteEvent);

                function registerLivewireListener() {
                    if (toastListenerRegistered) {
                        return;
                    }
                    if (window.Livewire && Livewire.on) {
                        toastListenerRegistered = true;
                        Livewire.on('notify', function () {
                            var normalized = normalizePayload(arguments);
                            if (normalized) {
                                showToast(normalized.type, normalized.message);
                            }
                        });
                    }
                }

                document.addEventListener('livewire:init', function () {
                    registerLivewireListener();
                });
                document.addEventListener('livewire:initialized', function () {
                    registerLivewireListener();
                });
                setTimeout(registerLivewireListener, 500);

                // Close modal event listener
                document.addEventListener('close-modal', function() {
                    var openModals = document.querySelectorAll('.modal.show');
                    openModals.forEach(function(modal) {
                        var modalInstance = bootstrap.Modal.getInstance(modal);
                        if (modalInstance) {
                            modalInstance.hide();
                        }
                    });
                });
            })();
        </script>

        @livewireScripts
        @stack('scripts')
    </body>
</html>
