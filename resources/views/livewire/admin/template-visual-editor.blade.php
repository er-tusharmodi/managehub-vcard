<div>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">
                        <i class="mdi mdi-pencil-box-outline me-2 text-primary"></i>Visual Editor:
                        <span class="text-primary">{{ $templateName }}</span>
                    </h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.templates.index') }}">Templates</a></li>
                            <li class="breadcrumb-item active">Visual Editor</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══════════════════════════════════════════════════
             SIDEBAR + EDIT PANEL LAYOUT
        ═══════════════════════════════════════════════════ -->
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

            $configIconMap = [
                'meta'            => ['icon' => 'mdi-tag-multiple-outline',   'color' => 'primary'],
                'location'        => ['icon' => 'mdi-map-marker',             'color' => 'danger'],
                'socialLinks'     => ['icon' => 'mdi-share-variant',          'color' => 'info'],
                'social'          => ['icon' => 'mdi-share-variant',          'color' => 'info'],
                'followLinks'     => ['icon' => 'mdi-share-variant',          'color' => 'info'],
                'services'        => ['icon' => 'mdi-briefcase',              'color' => 'primary'],
                'products'        => ['icon' => 'mdi-shopping',               'color' => 'success'],
                'gallery'         => ['icon' => 'mdi-image-multiple',         'color' => 'danger'],
                'hours'           => ['icon' => 'mdi-clock-outline',          'color' => 'warning'],
                'payments'        => ['icon' => 'mdi-credit-card',            'color' => 'primary'],
                'payment'         => ['icon' => 'mdi-credit-card',            'color' => 'primary'],
                'qr'              => ['icon' => 'mdi-qrcode',                 'color' => 'dark'],
                'contact'         => ['icon' => 'mdi-email-outline',          'color' => 'primary'],
                'contactSave'     => ['icon' => 'mdi-account-plus',           'color' => 'primary'],
                'story'           => ['icon' => 'mdi-book-open-outline',      'color' => 'secondary'],
                'menu'            => ['icon' => 'mdi-silverware-fork-knife',  'color' => 'success'],
                'MENU'            => ['icon' => 'mdi-silverware-fork-knife',  'color' => 'success'],
                'reserve'         => ['icon' => 'mdi-calendar-check',         'color' => 'success'],
                'appointment'     => ['icon' => 'mdi-calendar-check',         'color' => 'success'],
                'booking'         => ['icon' => 'mdi-calendar',               'color' => 'success'],
                'packages'        => ['icon' => 'mdi-package-variant',        'color' => 'info'],
                'barbers'         => ['icon' => 'mdi-account-multiple',       'color' => 'secondary'],
                'specializations' => ['icon' => 'mdi-star-outline',           'color' => 'warning'],
                'conditions'      => ['icon' => 'mdi-medical-bag',            'color' => 'danger'],
                'fees'            => ['icon' => 'mdi-cash',                   'color' => 'success'],
                'awards'          => ['icon' => 'mdi-trophy-outline',         'color' => 'warning'],
                'whyChoose'       => ['icon' => 'mdi-thumb-up-outline',       'color' => 'primary'],
                'categories'      => ['icon' => 'mdi-tag-outline',            'color' => 'secondary'],
                'picks'           => ['icon' => 'mdi-star-circle-outline',    'color' => 'success'],
                'deals'           => ['icon' => 'mdi-percent-outline',        'color' => 'danger'],
                'enquiry'         => ['icon' => 'mdi-comment-question-outline','color'=> 'primary'],
                'collections'     => ['icon' => 'mdi-diamond-outline',        'color' => 'info'],
                'purity'          => ['icon' => 'mdi-certificate-outline',    'color' => 'warning'],
                'certifications'  => ['icon' => 'mdi-shield-check-outline',   'color' => 'success'],
                'showroom'        => ['icon' => 'mdi-store-outline',          'color' => 'secondary'],
                'follow'          => ['icon' => 'mdi-share-variant',          'color' => 'info'],
                'brands'          => ['icon' => 'mdi-star-box-outline',       'color' => 'secondary'],
                'featuredProducts'=> ['icon' => 'mdi-shopping-outline',       'color' => 'success'],
                'repairServices'  => ['icon' => 'mdi-tools',                  'color' => 'warning'],
            ];
        @endphp

        <div class="row g-3">
            <!-- ─── LEFT SIDEBAR ─── -->
            <div class="col-lg-3">
                <div class="card" style="position:sticky;top:80px;">
                    <div class="card-header bg-primary text-white py-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="mdi mdi-palette font-size-20"></i>
                            <div class="overflow-hidden">
                                <p class="mb-0 fw-semibold text-truncate">{{ $templateName }}</p>
                                <small class="opacity-75">Template Editor</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="tve-nav" style="max-height:calc(100vh - 260px);overflow-y:auto;">
                            @foreach($sections as $tab)
                                @php
                                    $tabIcon  = ($iconMap[$tab] ?? ['icon' => 'mdi-layers', 'color' => 'primary']);
                                    $isActive = $section === $tab;
                                    $sectionLabelMap = [
                                        'restaurant-cafe-template' => ['R' => 'Business Details', 'MENU' => 'Menu'],
                                    ];
                    $tabLabel = match($tab) {
                                        '_common'   => 'Basic Info',
                                        '_settings' => 'Section Visibility',
                                        default     => ($sectionLabelMap[$templateKey][$tab] ?? \Illuminate\Support\Str::headline(str_replace('_', ' ', $tab))),
                                    };

                                    // Determine if this section has a toggle in _sections_config
                                    $hasToggle   = isset($sectionsConfig[$tab]);
                                    $tabEnabled  = $hasToggle ? ($sectionsConfig[$tab]['enabled'] ?? true) : null;
                                @endphp
                                <div wire:key="nav-{{ $tab }}"
                                     class="tve-nav-item d-flex align-items-center gap-2 px-3 py-2 cursor-pointer border-bottom
                                            {{ $isActive ? 'tve-nav-active' : '' }}
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
                        <a href="{{ route('admin.templates.edit.code', $templateKey) }}" class="btn btn-sm btn-outline-primary w-100">
                            <i class="mdi mdi-code-braces me-1"></i>Code Editor
                        </a>
                        <a href="{{ route('admin.templates.index') }}" class="btn btn-sm btn-outline-secondary w-100 mt-1">
                            <i class="mdi mdi-arrow-left me-1"></i>All Templates
                        </a>
                    </div>
                </div>
            </div>

            <!-- ─── RIGHT EDIT PANEL ─── -->
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header bg-light">
                        <div class="d-flex align-items-center gap-3">
                            @php
                                $activeIcon = $iconMap[$section] ?? ['icon' => 'mdi-layers', 'color' => 'primary'];
                                $activeLabel = match($section) {
                                    '_common'   => 'Basic Info',
                                    '_settings' => 'Section Visibility',
                                    'MENU'      => 'Menu',
                                    default     => \Illuminate\Support\Str::headline(str_replace('_', ' ', $section ?? '')),
                                };
                            @endphp
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title rounded-circle bg-soft-{{ $activeIcon['color'] }} text-{{ $activeIcon['color'] }} font-size-20">
                                    <i class="mdi {{ $activeIcon['icon'] }}"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-0">{{ $activeLabel }}</h5>
                                <small class="text-muted">Edit default template data for this section</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- Flash messages --}}
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

                        {{-- Section disabled warning --}}
                        @if(isset($sectionsConfig[$section]) && !($sectionsConfig[$section]['enabled'] ?? true))
                            <div class="alert alert-warning mb-3">
                                <i class="mdi mdi-eye-off-outline me-2"></i>
                                <strong>This section is currently disabled.</strong>
                                New vCards using this template will not show this section. Enable it in the <a href="#" wire:click.prevent="selectSection('_settings')" class="alert-link">Section Visibility</a> tab.
                            </div>
                        @endif

                        @if($section === '_settings')
                            {{-- ── SECTION VISIBILITY PANEL ─────────────────────────── --}}
                            <p class="text-muted mb-3" style="font-size:.85rem;">Toggle sections on or off. New vCards created with this template will respect these defaults.</p>
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
                                                        {{ $sEnabled ? 'Enabled by default' : 'Disabled by default' }}
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
                                    @php
                                        $isFormList = is_array($form) && !empty($form) && array_values($form) === $form;
                                        $assetBaseUrl = url("template-assets/{$templateKey}/");
                                    @endphp

                                    @php $formPartial = $this->resolveFormPartial(); @endphp
                                    @if($formPartial)
                                        @include($formPartial, ['assetBaseUrl' => $assetBaseUrl])
                                    @elseif($isFormList)
                                        @include('livewire.vcards.partials.field', [
                                            'key'             => $section,
                                            'value'           => $form,
                                            'wirePath'        => '',
                                            'assetBaseUrl'    => $assetBaseUrl,
                                            'categoryOptions' => $categoryOptions,
                                        ])
                                    @else
                                        @foreach ($form as $key => $value)
                                            @include('livewire.vcards.partials.field', [
                                                'key'          => $key,
                                                'value'        => $value,
                                                'wirePath'     => $key,
                                                'assetBaseUrl' => $assetBaseUrl,
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
                                        <i class="mdi mdi-loading mdi-spin me-1"></i>Saving…
                                    </span>
                                </button>
                                @if (!empty($uploads))
                                    <div class="alert alert-warning mb-0 py-2 px-3 d-inline-flex align-items-center">
                                        <i class="mdi mdi-information-outline me-2"></i>
                                        <small>You have uploaded files. Click "Save Changes" to apply them.</small>
                                    </div>
                                @endif
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div><!-- /row -->
    </div>

    <style>
        .tve-nav-item:hover:not(.tve-nav-active) { background: rgba(0,0,0,.04); }
        .tve-nav-active { background: rgba(var(--bs-primary-rgb),.08); border-left: 3px solid var(--bs-primary) !important; }
        .tve-nav-item { border-left: 3px solid transparent; }
        .border-success-subtle { border-color: rgba(var(--bs-success-rgb),.3) !important; }
        .bg-success-subtle { background-color: rgba(var(--bs-success-rgb),.07) !important; }
        .border-secondary-subtle { border-color: rgba(var(--bs-secondary-rgb),.2) !important; }
        .bg-secondary-subtle { background-color: rgba(var(--bs-secondary-rgb),.07) !important; }
    </style>

    <script>
    (function () {
        window.addEventListener('section-changed', function (e) {
            const section = e.detail.section;
            const base = window.location.pathname.replace(/\/visual(\/[^\/]*)?$/, '');
            history.pushState({ section: section }, '', base + '/visual/' + encodeURIComponent(section));
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

    // ── Sortable drag-to-reorder for list sections ────────────────────────
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
                        if (comp) { comp.call('reorderRow', path, evt.oldIndex, evt.newIndex); }
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
        loadSortableAndInit();
        if (!window.__vcardSortableListenerAdded) {
            window.__vcardSortableListenerAdded = true;
            var _mo = new MutationObserver(function () { loadSortableAndInit(); });
            _mo.observe(document.body, { childList: true, subtree: true });
        }
    })();

    // ── Generic Item Modal (services / products / etc.) ───────────────────
    window._ssItemModalOpen = window._ssItemModalOpen || false;
    window._ssItemModalWasOpen = false;
    document.addEventListener('open-item-modal', function (e) {
        var el = document.getElementById('ss-item-modal');
        if (!el || !window.bootstrap) return;
        window._ssItemModalOpen = true;
        bootstrap.Modal.getOrCreateInstance(el).show();
    });
    document.addEventListener('hide-item-modal', function () {
        var el = document.getElementById('ss-item-modal');
        if (!el || !window.bootstrap) return;
        window._ssItemModalOpen = false;
        bootstrap.Modal.getOrCreateInstance(el).hide();
    });
    document.addEventListener('hidden.bs.modal', function(e) {
        if (e.target && e.target.id === 'ss-item-modal') window._ssItemModalOpen = false;
    });
    document.addEventListener('livewire:updating', function() {
        var el = document.getElementById('ss-item-modal');
        window._ssItemModalWasOpen = !!(el && el.classList.contains('show'));
    });
    document.addEventListener('livewire:updated', function() {
        if (window._ssItemModalOpen || window._ssItemModalWasOpen) {
            var el = document.getElementById('ss-item-modal');
            if (el && window.bootstrap) {
                window._ssItemModalOpen = true;
                window._ssItemModalWasOpen = false;
                bootstrap.Modal.getOrCreateInstance(el).show();
            }
        }
    });
    </script>
</div>
