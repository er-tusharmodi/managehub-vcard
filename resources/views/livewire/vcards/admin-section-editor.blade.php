<div>
    @section('content-wrapper-class', 'container-fluid')
    @section('title', 'vCard Content Editor')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">
                    <i class="mdi mdi-card-account-details-outline me-2 text-primary"></i>vCard Content Editor
                    <span class="text-muted fw-normal fs-6 ms-2">{{ $vcard->subdomain }}.{{ $baseDomain }}</span>
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.vcards.index') }}">vCards</a></li>
                        <li class="breadcrumb-item active">Edit Data</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

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
            'menu'            => ['icon' => 'mdi-silverware-fork-knife',  'color' => 'success'],
            'MENU'            => ['icon' => 'mdi-silverware-fork-knife',  'color' => 'success'],
            'brands'          => ['icon' => 'mdi-star-box-outline',       'color' => 'secondary'],
            'collections'     => ['icon' => 'mdi-diamond-outline',        'color' => 'info'],
            'showroom'        => ['icon' => 'mdi-store-outline',          'color' => 'secondary'],
            'director'        => ['icon' => 'mdi-account-star',           'color' => 'warning'],
            'R'               => ['icon' => 'mdi-store',                  'color' => 'success'],
            '_settings'       => ['icon' => 'mdi-tune-vertical',          'color' => 'secondary'],
        ];
    @endphp

    <div class="row g-3">

        {{-- LEFT SIDEBAR --}}
        <div class="col-lg-3">
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

                        <div class="px-3 pt-2 pb-1">
                            <small class="text-uppercase text-muted fw-semibold" style="font-size:.68rem;letter-spacing:.06em;">Sections</small>
                        </div>

                        @foreach ($sections as $tab)
                            @php
                                $tabIcon  = $iconMap[$tab] ?? ['icon' => 'mdi-layers', 'color' => 'primary'];
                                $isActive = ($editMode !== 'code') && $section === $tab;
                                $sectionLabelMap = [
                                    'restaurant-cafe-template' => ['R' => 'Business Details', 'MENU' => 'Menu', 'profile' => 'Profile Categories', 'qr' => 'QR Text'],
                                ];
                                $tabLabel = $tab === '_common'
                                    ? 'Basic Info'
                                    : ($sectionLabelMap[$vcard->template_key ?? ''][$tab] ?? \Illuminate\Support\Str::headline(str_replace('_', ' ', $tab)));

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
                        <a href="{{ route('admin.vcards.index') }}" class="btn btn-sm btn-outline-secondary flex-grow-1">
                            <i class="mdi mdi-arrow-left me-1"></i>All vCards
                        </a>
                        <a href="{{ route('vcard.public.path', ['subdomain' => $vcard->subdomain]) }}" target="_blank"
                           class="btn btn-sm btn-outline-primary" title="View vCard">
                            <i class="mdi mdi-eye-outline"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT EDIT PANEL --}}
        <div class="col-lg-9 col-xxl-9">

            {{-- VISUAL EDITOR --}}
            @php
                    $activeIcon  = $iconMap[$section] ?? ['icon' => 'mdi-layers', 'color' => 'primary'];
                    $activeLabel = match($section) {
                        '_common'   => 'Basic Info',
                        '_settings' => 'Section Visibility',
                        'MENU'      => 'Menu',
                        default     => \Illuminate\Support\Str::headline(str_replace('_', ' ', $section ?? '')),
                    };
                    $isFormList  = is_array($form) && !empty($form) && array_values($form) === $form;
                    $itemLabel   = \Illuminate\Support\Str::singular($activeLabel);
                @endphp

                @if (empty($sectionsConfig))
                    <div class="alert alert-info border-0 mb-3 d-flex align-items-start gap-2">
                        <i class="mdi mdi-information-outline mt-1 fs-5"></i>
                        <div>
                            <strong>Section configuration not available.</strong>
                            Enable/disable toggles will appear after running:
                            <code class="ms-1">php artisan vcards:sync-sections</code>
                        </div>
                    </div>
                @endif

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
                                <small class="text-muted">Edit vCard content for this section</small>
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
                            <form wire:submit.prevent="save" novalidate wire:key="section-form-{{ $section }}">
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
            const base = window.location.pathname.replace(/\/data(\/[^\/]*)?$/, '');
            history.pushState({ section: section }, '', base + '/data/' + encodeURIComponent(section));
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

    // ── Sortable drag-to-reorder for gallery grids ──────────────────────
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
                    onEnd: function (evt) {
                        if (evt.oldIndex === evt.newIndex) { return; }
                        // Re-index data-row-index on children so event delegation stays correct
                        Array.from(container.children).forEach(function(el, idx) {
                            if ('rowIndex' in el.dataset) el.dataset.rowIndex = idx;
                        });
                        var comp = window.Livewire.find(wireEl.getAttribute('wire:id'));
                        if (comp) {
                            comp.call('reorderRow', path, evt.oldIndex, evt.newIndex);
                        }
                    }
                });
            });
        }
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

    // Generic item modal (sweetshop services / products — ss-item-modal)
    // Track open state so we can re-open after Livewire re-renders (e.g. image upload)
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
        bootstrap.Modal.getOrCreateInstance(el).hide();
    });
    document.addEventListener('hidden.bs.modal', function(e) {
        if (e.target && e.target.id === 'ss-item-modal') window._ssItemModalOpen = false;
    });
    document.addEventListener('livewire:updated', function() {
        if (window._ssItemModalOpen) {
            var el = document.getElementById('ss-item-modal');
            if (el) bootstrap.Modal.getOrCreateInstance(el).show();
        }
    });
    </script>
</div>
