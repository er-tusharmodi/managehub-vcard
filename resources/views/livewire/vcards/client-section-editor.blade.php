<div>
    @php
        $iconMap = [
            '_common'         => ['icon' => 'mdi-information-outline',   'color' => 'primary'],
            'meta'            => ['icon' => 'mdi-tag-multiple-outline',   'color' => 'primary'],
            'shop'            => ['icon' => 'mdi-store',                  'color' => 'success'],
            'assets'          => ['icon' => 'mdi-image',                  'color' => 'danger'],
            'banner'          => ['icon' => 'mdi-panorama',               'color' => 'warning'],
            'profile'         => ['icon' => 'mdi-account',                'color' => 'info'],
            'hero'            => ['icon' => 'mdi-star-shooting',          'color' => 'warning'],
            'sections'        => ['icon' => 'mdi-layers',                 'color' => 'primary'],
            'labels'          => ['icon' => 'mdi-tag-multiple',           'color' => 'success'],
            'status'          => ['icon' => 'mdi-clock',                  'color' => 'warning'],
            'services'        => ['icon' => 'mdi-briefcase',              'color' => 'primary'],
            'packages'        => ['icon' => 'mdi-package',                'color' => 'info'],
            'products'        => ['icon' => 'mdi-shopping',               'color' => 'success'],
            'gallery'         => ['icon' => 'mdi-image-multiple',         'color' => 'danger'],
            'appointment'     => ['icon' => 'mdi-calendar-check',         'color' => 'success'],
            'booking'         => ['icon' => 'mdi-calendar',               'color' => 'success'],
            'hours'           => ['icon' => 'mdi-clock-outline',          'color' => 'warning'],
            'location'        => ['icon' => 'mdi-map-marker',             'color' => 'danger'],
            'social'          => ['icon' => 'mdi-share-variant',          'color' => 'info'],
            'payments'        => ['icon' => 'mdi-credit-card',            'color' => 'primary'],
            'payment'         => ['icon' => 'mdi-credit-card',            'color' => 'primary'],
            'doctor'          => ['icon' => 'mdi-hospital-box',           'color' => 'danger'],
            'barbers'         => ['icon' => 'mdi-account-multiple',       'color' => 'info'],
            'specializations' => ['icon' => 'mdi-star',                   'color' => 'danger'],
            'awards'          => ['icon' => 'mdi-trophy',                 'color' => 'warning'],
            'testimonials'    => ['icon' => 'mdi-comment-multiple',       'color' => 'info'],
            'footer'          => ['icon' => 'mdi-page-layout-footer',     'color' => 'secondary'],
            'floatingBar'     => ['icon' => 'mdi-dock-bottom',            'color' => 'secondary'],
            'cart'            => ['icon' => 'mdi-cart',                   'color' => 'success'],
            'share'           => ['icon' => 'mdi-share',                  'color' => 'info'],
            'toast'           => ['icon' => 'mdi-bell-outline',           'color' => 'warning'],
            'messages'        => ['icon' => 'mdi-message-text',           'color' => 'primary'],
            'counters'        => ['icon' => 'mdi-counter',                'color' => 'info'],
            'whyChoose'       => ['icon' => 'mdi-thumb-up-outline',       'color' => 'primary'],
            'courses'         => ['icon' => 'mdi-book-open-variant',      'color' => 'success'],
            'batches'         => ['icon' => 'mdi-calendar-multiselect',   'color' => 'warning'],
            'demo'            => ['icon' => 'mdi-video-outline',          'color' => 'danger'],
            'fees'            => ['icon' => 'mdi-cash',                   'color' => 'success'],
            'faculty'         => ['icon' => 'mdi-account-tie',            'color' => 'info'],
            'faq'             => ['icon' => 'mdi-help-circle-outline',    'color' => 'secondary'],
            'qr'              => ['icon' => 'mdi-qrcode',                 'color' => 'dark'],
            'contact'         => ['icon' => 'mdi-email-outline',          'color' => 'primary'],
            'follow'          => ['icon' => 'mdi-account-plus-outline',   'color' => 'info'],
            'menu'            => ['icon' => 'mdi-food',                   'color' => 'warning'],
            'brands'          => ['icon' => 'mdi-star-box-outline',       'color' => 'secondary'],
            'collections'     => ['icon' => 'mdi-diamond-outline',        'color' => 'info'],
            'showroom'        => ['icon' => 'mdi-store-outline',          'color' => 'secondary'],
            'director'        => ['icon' => 'mdi-account-star',           'color' => 'warning'],
            'trust'           => ['icon' => 'mdi-shield-check-outline',   'color' => 'success'],
            'materials'       => ['icon' => 'mdi-bookshelf',               'color' => 'primary'],
            'modes'           => ['icon' => 'mdi-school-outline',          'color' => 'info'],
            'socialLinks'     => ['icon' => 'mdi-share-variant',           'color' => 'info'],
            'businessHours'   => ['icon' => 'mdi-clock-outline',           'color' => 'warning'],
            'paymentMethods'  => ['icon' => 'mdi-credit-card',             'color' => 'primary'],
            'categories'      => ['icon' => 'mdi-tag-outline',             'color' => 'secondary'],
            'conditions'      => ['icon' => 'mdi-medical-bag',             'color' => 'danger'],
            'R'               => ['icon' => 'mdi-store',                   'color' => 'success'],
            '_settings'       => ['icon' => 'mdi-tune-vertical',           'color' => 'secondary'],
        ];
    @endphp

    @php
        $sectionLabels = [
            '_common'     => 'Basic Info',
            '_settings'   => 'Section Visibility',
            'assets'      => 'Images & Photos',
            'banner'      => 'Cover Image',
            'meta'        => 'SEO & Meta Tags',
            'floatingBar' => 'Quick Action Bar',
            'whyChoose'   => 'Why Choose Us',
            'qr'          => 'QR Text',
            'shop'        => 'Shop Settings',
            'payments'    => 'Payment Options',
            'payment'     => 'Payment Options',
            'hours'       => 'Opening Hours',
            'location'    => 'Address & Map',
            'social'      => 'Social Links',
            'footer'      => 'Footer Info',
            'hero'        => 'Hero Section',
            'follow'      => 'Follow Us',
            'contact'     => 'Contact Info',
            'trust'       => 'Trust & Credentials',
            'materials'   => 'Study Materials',
            'modes'       => 'Learning Modes',
            'messages'    => 'Messages',
            'socialLinks'    => 'Social Links',
            'businessHours'  => 'Business Hours',
            'paymentMethods' => 'Payment Methods',
            'categories'     => 'Categories',
            'conditions'     => 'Conditions Treated',
        ];
    @endphp

    {{-- MOBILE TOP BAR --}}
    @if(!$solo)
    <div class="d-flex d-lg-none align-items-center justify-content-between mb-3 gap-2">
        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#cseSectionDrawer">
            <i class="mdi mdi-menu me-1"></i>Sections
        </button>
        <a href="{{ route('vcard.public.path', ['subdomain' => $vcard->subdomain]) }}" target="_blank" class="btn btn-sm btn-outline-primary">
            <i class="mdi mdi-eye-outline me-1"></i>View vCard
        </a>
    </div>
    @endif

    {{-- MOBILE SECTION NAV OFFCANVAS --}}
    @if(!$solo)
    <div class="offcanvas offcanvas-start" tabindex="-1" id="cseSectionDrawer" style="max-width:280px;">
        <div class="offcanvas-header bg-primary text-white py-3">
            <h6 class="offcanvas-title mb-0">
                <i class="mdi mdi-layers me-2"></i>{{ $vcard->name ?? $vcard->subdomain }}
            </h6>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0" style="overflow-y:auto;">
            @foreach ($sections as $tab)
                @php
                    $mobIcon    = $iconMap[$tab] ?? ['icon' => 'mdi-layers', 'color' => 'primary'];
                    $mobActive  = $section === $tab;
                    $mobLabel   = $sectionLabels[$tab] ?? ($tab === '_common' ? 'Basic Info' : \Illuminate\Support\Str::headline(str_replace('_', ' ', $tab)));
                    $mobEnabled = isset($sectionsConfig[$tab]) ? ($sectionsConfig[$tab]['enabled'] ?? true) : null;
                @endphp
                <div wire:key="mob-nav-{{ $tab }}"
                     class="d-flex align-items-center gap-2 px-3 py-2 border-bottom
                            {{ $mobActive ? 'bg-primary bg-opacity-10' : '' }}
                            {{ $mobEnabled === false ? 'opacity-50' : '' }}"
                     wire:click="selectSection('{{ $tab }}')"
                     data-bs-dismiss="offcanvas"
                     style="cursor:pointer;min-height:44px;{{ $mobActive ? 'border-left:3px solid var(--bs-primary)!important;' : '' }}">
                    <span class="avatar-xs">
                        <span class="avatar-title rounded-circle font-size-14
                                     {{ $mobActive ? 'bg-primary text-white' : 'text-'.$mobIcon['color'] }}">
                            <i class="mdi {{ $mobIcon['icon'] }}"></i>
                        </span>
                    </span>
                    <span class="fw-medium {{ $mobActive ? 'text-primary' : '' }}" style="font-size:.875rem;">{{ $mobLabel }}</span>
                    @if($mobEnabled === false)
                        <span class="ms-auto badge bg-secondary" style="font-size:.65rem;">Off</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="row g-3">

        {{-- LEFT SIDEBAR --}}
        @if(!$solo)
        <div class="col-lg-3 d-none d-lg-block">
            <div class="card" style="position:sticky;top:80px;">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="mdi mdi-pencil-box-outline font-size-20"></i>
                        <div class="overflow-hidden">
                            <p class="mb-0 fw-semibold text-truncate">{{ $vcard->name ?? $vcard->subdomain }}</p>
                            <small class="opacity-75">Content Editor</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="ase-nav" style="max-height:calc(100vh - 300px);overflow-y:auto;">

                        @foreach ($sections as $tab)
                            @php
                                $tabIcon  = $iconMap[$tab] ?? ['icon' => 'mdi-layers', 'color' => 'primary'];
                                $isActive = $section === $tab;
                                $sectionLabelMap = [
                                    'restaurant-cafe-template' => ['R' => 'Business Details', 'MENU' => 'Menu', 'profile' => 'Profile Categories'],
                                ];
                                $tabLabel = $sectionLabels[$tab]
                                    ?? ($sectionLabelMap[$vcard->template_key ?? ''][$tab] ?? null)
                                    ?? ($tab === '_common'
                                        ? 'Basic Info'
                                        : \Illuminate\Support\Str::headline(str_replace('_', ' ', $tab)));

                                $hasToggle  = isset($sectionsConfig[$tab]);
                                $tabEnabled = $hasToggle ? ($sectionsConfig[$tab]['enabled'] ?? true) : null;
                            @endphp
                            <div wire:key="nav-{{ $tab }}"
                                 class="ase-nav-item d-flex align-items-center gap-2 px-3 py-2 border-bottom
                                        {{ $isActive ? 'ase-nav-active' : '' }}
                                        {{ $hasToggle && !$tabEnabled ? 'opacity-50' : '' }}"
                                 wire:click="selectSection('{{ $tab }}')"
                                 style="cursor:pointer;min-height:48px;transition:background .15s;">
                                <div class="flex-shrink-0">
                                    <span class="avatar-xs">
                                        <span class="avatar-title rounded-circle font-size-14
                                                     {{ $isActive ? 'bg-primary text-white' : 'text-'.$tabIcon['color'] }}">
                                            <i class="mdi {{ $tabIcon['icon'] }}"></i>
                                        </span>
                                    </span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <p class="mb-0 fw-medium text-truncate {{ $isActive ? 'text-primary' : '' }}" style="font-size:.875rem;">
                                        {{ $tabLabel }}
                                    </p>
                                    @if($hasToggle)
                                        <small class="{{ $tabEnabled ? 'text-success' : 'text-muted' }}" style="font-size:.72rem;">
                                            {{ $tabEnabled ? '● Active' : '○ Disabled' }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
                <div class="card-footer py-2 px-3">
                    <div class="d-flex gap-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary flex-grow-1">
                            <i class="mdi mdi-arrow-left me-1"></i>My vCards
                        </a>
                        <a href="{{ route('vcard.public.path', ['subdomain' => $vcard->subdomain]) }}" target="_blank"
                           class="btn btn-sm btn-outline-primary" title="View vCard">
                            <i class="mdi mdi-eye-outline"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- RIGHT EDIT PANEL --}}
        <div class="{{ $solo ? 'col-12' : 'col-lg-9' }}">

            @if ($subscriptionBlocked)
                {{-- SUBSCRIPTION BLOCKED --}}
                <div class="card">
                    <div class="card-body py-5 text-center">
                        <div class="avatar-lg mx-auto mb-4">
                            <span class="avatar-title rounded-circle bg-soft-warning text-warning font-size-28">
                                <i class="mdi mdi-lock-outline"></i>
                            </span>
                        </div>
                        <h5 class="text-warning mb-2">Subscription Inactive</h5>
                        <p class="text-muted mb-0">{{ $subscriptionMessage }}</p>
                    </div>
                </div>

            @else
                {{-- VISUAL EDITOR --}}
                @php
                    $activeIcon  = $iconMap[$section] ?? ['icon' => 'mdi-layers', 'color' => 'primary'];
                    $sectionLabelMapActive = [
                        'restaurant-cafe-template' => ['R' => 'Business Details', 'MENU' => 'Menu', 'profile' => 'Profile Categories'],
                    ];
                    $activeLabel = match($section) {
                        '_common'   => 'Basic Info',
                        '_settings' => 'Section Visibility',
                        default     => ($sectionLabelMapActive[$vcard->template_key ?? ''][$section] ?? $sectionLabels[$section] ?? \Illuminate\Support\Str::headline(str_replace('_', ' ', $section ?? ''))),
                    };
                    $isFormList  = is_array($form) && !empty($form) && array_values($form) === $form;
                    $itemLabel   = \Illuminate\Support\Str::singular($activeLabel);
                @endphp

                <div class="card">
                    <div class="card-header bg-light">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title rounded-circle bg-soft-{{ $activeIcon['color'] }} text-{{ $activeIcon['color'] }} font-size-20">
                                    <i class="mdi {{ $activeIcon['icon'] }}"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-0">{{ $activeLabel }}</h5>
                                <small class="text-muted">Edit your vCard content for this section</small>
                            </div>
                            @if ($isFormList)
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                                    <i class="mdi mdi-plus me-1"></i>Add {{ $itemLabel }}
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-alert-circle me-2"></i>{{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-alert-circle me-2"></i><strong>Please fix the following errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(isset($sectionsConfig[$section]) && !($sectionsConfig[$section]['enabled'] ?? true))
                            <div class="alert alert-warning mb-3">
                                <i class="mdi mdi-eye-off-outline me-2"></i>
                                <strong>This section is currently hidden.</strong>
                                Enable it in the <a href="#" wire:click.prevent="selectSection('_settings')" class="alert-link">Section Visibility</a> tab.
                            </div>
                        @endif

                        @if($section === '_settings')
                            {{-- ── SECTION VISIBILITY PANEL ─────────────────────────── --}}
                            <p class="text-muted mb-3" style="font-size:.85rem;">Toggle sections on or off to show or hide them on your vCard.</p>
                            <div class="row g-2">
                                @foreach($sectionsConfig as $sKey => $sCfg)
                                    @php
                                        $sEnabled = $sCfg['enabled'] ?? true;
                                        $sLabel   = $sCfg['label'] ?? \Illuminate\Support\Str::headline($sKey);
                                        $sIcon    = $iconMap[$sKey] ?? ['icon' => 'mdi-layers', 'color' => 'secondary'];
                                    @endphp
                                    <div class="col-12">
                                        <div class="d-flex align-items-center justify-content-between p-3 border rounded
                                                    {{ $sEnabled ? 'border-success-subtle bg-success-subtle' : 'border-secondary-subtle bg-light' }}"
                                             style="transition:.15s;">
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="avatar-sm flex-shrink-0">
                                                    <span class="avatar-title rounded-circle font-size-16
                                                                 {{ $sEnabled ? 'bg-success text-white' : 'bg-secondary-subtle text-secondary' }}">
                                                        <i class="mdi {{ $sIcon['icon'] }}"></i>
                                                    </span>
                                                </span>
                                                <div>
                                                    <p class="mb-0 fw-semibold" style="font-size:.9rem;">{{ $sLabel }}</p>
                                                    <small class="{{ $sEnabled ? 'text-success' : 'text-muted' }}">
                                                        {{ $sEnabled ? 'Visible on vCard' : 'Hidden from vCard' }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div wire:click="toggleSection('{{ $sKey }}')" style="cursor:pointer;">
                                                <div class="form-check form-switch mb-0">
                                                    <input class="form-check-input" type="checkbox"
                                                           style="width:44px;height:22px;cursor:pointer;pointer-events:none;"
                                                           @checked($sEnabled)>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <form wire:submit.prevent="save" novalidate>
                            <div class="row">
                                @if (empty($form))
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <i class="mdi mdi-information-outline me-2"></i>No data available for this section.
                                        </div>
                                    </div>
                                @else
                                    @php $formPartial = $this->resolveFormPartial(); @endphp
                                    @if($formPartial)
                                        @include($formPartial)
                                    @elseif($isFormList)
                                        @include('livewire.vcards.partials.field', [
                                            'key'                  => $section,
                                            'value'                => $form,
                                            'wirePath'             => '',
                                            'categoryOptions'      => $categoryOptions,
                                            'hideListHeaderButton' => true,
                                        ])
                                    @else
                                        @foreach ($form as $key => $value)
                                            @include('livewire.vcards.partials.field', [
                                                'key'             => $key,
                                                'value'           => $value,
                                                'wirePath'        => $key,
                                                'categoryOptions' => $categoryOptions,
                                            ])
                                        @endforeach
                                    @endif
                                @endif
                            </div>

                            <div class="mt-4 d-flex align-items-center gap-2 border-top pt-4">
                                <button class="btn btn-primary" type="submit" wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="save">
                                        <i class="mdi mdi-content-save me-1"></i>Save Changes
                                    </span>
                                    <span wire:loading wire:target="save">
                                        <i class="mdi mdi-loading mdi-spin me-1"></i>Saving...
                                    </span>
                                </button>
                                @if (!empty($uploads))
                                    <div class="alert alert-warning mb-0 py-2 px-3 d-inline-flex align-items-center">
                                        <i class="mdi mdi-information-outline me-2"></i>
                                        <small>Pending uploads — click "Save Changes" to apply.</small>
                                    </div>
                                @endif
                            </div>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

        </div>

    </div>

    <style>
        .ase-nav-item:hover:not(.ase-nav-active):not(.ase-nav-active-dark) { background: rgba(0,0,0,.04); }
        .ase-nav-active { background: rgba(var(--bs-primary-rgb),.08); border-left: 3px solid var(--bs-primary) !important; }
        .ase-nav-active-dark { background: rgba(0,0,0,.06); border-left: 3px solid #343a40 !important; }
        .ase-nav-item { border-left: 3px solid transparent; }
    </style>

    <script>
    (function () {
        window.addEventListener('section-changed', function (e) {
            const section = e.detail.section;
            // Guard: ignore any value that looks like a file path or url() CSS function
            if (!section || section.startsWith('url(') || section.startsWith('/') || section.includes('/storage/')) return;
            const base = window.location.pathname.replace(/\/edit(\/[^\/]*)?$/, '');
            history.pushState({ section: section }, '', base + '/edit/' + encodeURIComponent(section));
        });
        window.addEventListener('popstate', function (e) {
            if (e.state && e.state.section) {
                const el = document.querySelector('[wire\\:id]');
                if (el && window.Livewire) {
                    window.Livewire.find(el.getAttribute('wire:id')).call('selectSection', e.state.section);
                }
            }
        });
    })();

    // ── Sortable drag-to-reorder for list tables and grids ──────────────────────
    (function () {
        function initSortables() {
            if (!window.Sortable) { return; }
            document.querySelectorAll('[data-sort-path]').forEach(function (container) {
                if (container.__sortableInited) { return; }
                container.__sortableInited = true;
                var path = container.getAttribute('data-sort-path') || '';
                var wireEl = container.closest('[wire\\:id]');
                if (!wireEl) { return; }
                new Sortable(container, {
                    handle: '.drag-handle',
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    onEnd: function (evt) {
                        if (evt.oldIndex === evt.newIndex) { return; }
                        var comp = window.Livewire.find(wireEl.getAttribute('wire:id'));
                        if (comp) {
                            comp.call('reorderRow', path, evt.oldIndex, evt.newIndex);
                        }
                    }
                });
            });
        }

        // Load SortableJS on demand then init
        function loadSortableAndInit() {
            if (window.Sortable) { initSortables(); return; }
            var script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js';
            script.onload = initSortables;
            document.head.appendChild(script);
        }

        // Run once immediately at page load
        loadSortableAndInit();
        // Re-run whenever Sortable containers appear/reappear in the DOM
        // (MutationObserver is immune to Livewire version differences)
        if (!window.__vcardSortableListenerAdded) {
            window.__vcardSortableListenerAdded = true;
            var _mo = new MutationObserver(function () {
                loadSortableAndInit();
            });
            _mo.observe(document.body, { childList: true, subtree: true });
        }
    })();

    // ── Generic item modal (ss-item-modal — used by restaurant MENU, sweetshop products, etc.) ──
    (function () {
        window._ssItemModalOpen = window._ssItemModalOpen || false;
        window.addEventListener('open-item-modal', function (e) {
            var el = document.getElementById('ss-item-modal');
            if (!el) return;
            window._ssItemModalOpen = true;
            bootstrap.Modal.getOrCreateInstance(el).show();
        });
        window.addEventListener('hide-item-modal', function () {
            var el = document.getElementById('ss-item-modal');
            if (!el) return;
            window._ssItemModalOpen = false;
            var inst = bootstrap.Modal.getInstance(el);
            if (inst) inst.hide();
        });
        document.addEventListener('hidden.bs.modal', function (e) {
            if (e.target && e.target.id === 'ss-item-modal') window._ssItemModalOpen = false;
        });
        document.addEventListener('livewire:updated', function () {
            if (window._ssItemModalOpen) {
                var el = document.getElementById('ss-item-modal');
                if (el) bootstrap.Modal.getOrCreateInstance(el).show();
            }
        });
    })();
    </script>
</div>
