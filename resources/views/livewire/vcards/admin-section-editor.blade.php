<div>
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
            'menu'            => ['icon' => 'mdi-food',                   'color' => 'warning'],
            'brands'          => ['icon' => 'mdi-star-box-outline',       'color' => 'secondary'],
            'collections'     => ['icon' => 'mdi-diamond-outline',        'color' => 'info'],
            'showroom'        => ['icon' => 'mdi-store-outline',          'color' => 'secondary'],
            'director'        => ['icon' => 'mdi-account-star',           'color' => 'warning'],
            'R'               => ['icon' => 'mdi-store',                  'color' => 'success'],
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

                        {{-- Code Editor nav item --}}
                        <div wire:key="nav-code-editor"
                             class="ase-nav-item d-flex align-items-center gap-2 px-3 py-2 border-bottom
                                    {{ $editMode === 'code' ? 'ase-nav-active-dark' : '' }}"
                             wire:click="switchToCodeEditor"
                             style="cursor:pointer;min-height:48px;transition:background .15s;">
                            <div class="flex-shrink-0">
                                <span class="avatar-xs">
                                    <span class="avatar-title rounded-circle font-size-14
                                                 {{ $editMode === 'code' ? 'bg-dark text-white' : 'bg-soft-dark text-dark' }}">
                                        <i class="mdi mdi-code-braces"></i>
                                    </span>
                                </span>
                            </div>
                            <div class="flex-grow-1 overflow-hidden">
                                <p class="mb-0 fw-medium text-truncate {{ $editMode === 'code' ? 'text-dark' : '' }}" style="font-size:.875rem;">
                                    Code Editor
                                </p>
                                <small class="text-muted" style="font-size:.72rem;">Raw JSON data</small>
                            </div>
                        </div>

                        <div class="px-3 pt-2 pb-1">
                            <small class="text-uppercase text-muted fw-semibold" style="font-size:.68rem;letter-spacing:.06em;">Sections</small>
                        </div>

                        @foreach ($sections as $tab)
                            @php
                                $tabIcon  = $iconMap[$tab] ?? ['icon' => 'mdi-layers', 'color' => 'primary'];
                                $isActive = ($editMode !== 'code') && $section === $tab;
                                $sectionLabelMap = [
                                    'restaurant-cafe-template' => ['R' => 'Business Details'],
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
                                                     {{ $isActive ? 'bg-primary text-white' : 'bg-soft-'.$tabIcon['color'].' text-'.$tabIcon['color'] }}">
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
                                @if($hasToggle)
                                    <div class="flex-shrink-0" wire:click.stop="toggleSection('{{ $tab }}')">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox"
                                                   style="width:34px;height:18px;cursor:pointer;pointer-events:none;"
                                                   @checked($tabEnabled)>
                                        </div>
                                    </div>
                                @endif
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
        <div class="col-lg-9 col-xxl-5">

            @if ($editMode === 'code')
                {{-- CODE EDITOR --}}
                <div class="card">
                    <div class="card-header bg-light">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title rounded-circle bg-dark text-white font-size-20">
                                    <i class="mdi mdi-code-braces"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-0">Code Editor</h5>
                                <small class="text-muted">Edit complete vCard JSON data</small>
                            </div>
                            <button wire:click="switchToVisualEditor" class="btn btn-sm btn-outline-primary">
                                <i class="mdi mdi-eye me-1"></i>Switch to Visual Editor
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="alert alert-warning m-3 mb-0 border-0">
                            <i class="mdi mdi-alert-outline me-2"></i>
                            <strong>Caution:</strong> You are editing raw JSON. Invalid JSON will not be saved. Changes affect the entire vCard data structure.
                        </div>
                        <textarea
                            wire:model="jsonContent"
                            class="form-control font-monospace"
                            style="min-height:600px;border:none;border-radius:0;font-size:13px;line-height:1.6;tab-size:4;"
                            spellcheck="false"
                        ></textarea>
                    </div>
                    <div class="card-footer bg-white border-top py-3">
                        <div class="d-flex gap-2">
                            <button wire:click="saveCodeEditor" class="btn btn-success px-4">
                                <span wire:loading.remove wire:target="saveCodeEditor">
                                    <i class="mdi mdi-content-save me-1"></i>Save JSON
                                </span>
                                <span wire:loading wire:target="saveCodeEditor">
                                    <i class="mdi mdi-loading mdi-spin me-1"></i>Saving...
                                </span>
                            </button>
                            <button wire:click="switchToVisualEditor" type="button" class="btn btn-light px-4">
                                <i class="mdi mdi-close me-1"></i>Cancel
                            </button>
                        </div>
                    </div>
                </div>

            @else
                {{-- VISUAL EDITOR --}}
                @php
                    $activeIcon  = $iconMap[$section] ?? ['icon' => 'mdi-layers', 'color' => 'primary'];
                    $activeLabel = $section === '_common'
                        ? 'Basic Info'
                        : \Illuminate\Support\Str::headline(str_replace('_', ' ', $section ?? ''));
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
                            @if(isset($sectionsConfig[$section]))
                                @php $secEnabled = $sectionsConfig[$section]['enabled'] ?? true; @endphp
                                <div wire:click="toggleSection('{{ $section }}')" style="cursor:pointer;">
                                    <span class="badge rounded-pill {{ $secEnabled ? 'bg-success' : 'bg-secondary' }} px-3 py-2">
                                        <i class="mdi {{ $secEnabled ? 'mdi-toggle-switch' : 'mdi-toggle-switch-off' }} me-1"></i>
                                        {{ $secEnabled ? 'Section Enabled' : 'Section Disabled' }}
                                    </span>
                                </div>
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
                                <strong>This section is currently disabled.</strong>
                                It will not be visible on the vCard. Toggle it on using the switch above or in the sidebar.
                            </div>
                        @endif

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
                    </div>
                </div>
            @endif

        </div>

        {{-- LIVE PREVIEW PANEL --}}
        <div class="col-xxl-4 d-none d-xxl-block">
            <div class="card" style="position:sticky;top:80px;">
                <div class="card-header bg-light py-2 px-3 d-flex align-items-center justify-content-between">
                    <span class="fw-semibold" style="font-size:.875rem;">
                        <i class="mdi mdi-cellphone me-1 text-primary"></i>Live Preview
                    </span>
                    <a href="{{ route('vcard.public.path', ['subdomain' => $vcard->subdomain]) }}" target="_blank"
                       class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size:.75rem;">
                        <i class="mdi mdi-open-in-new me-1"></i>Open
                    </a>
                </div>
                <div class="card-body p-0" style="background:#f0f0f0;">
                    <iframe id="vcardPreviewFrame"
                            src="{{ route('vcard.public.path', ['subdomain' => $vcard->subdomain]) }}"
                            style="width:100%;height:calc(100vh - 220px);min-height:500px;border:0;display:block;"
                            loading="lazy"></iframe>
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
        window.addEventListener('vcard-saved', function () {
            const frame = document.getElementById('vcardPreviewFrame');
            if (frame) { frame.src = frame.src; }
        });
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
    </script>
</div>
