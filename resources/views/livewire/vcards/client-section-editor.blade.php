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
                    <div class="cse-nav" style="max-height:calc(100vh - 260px);overflow-y:auto;">

                        @foreach ($sections as $tab)
                            @php
                                $tabIcon  = $iconMap[$tab] ?? ['icon' => 'mdi-layers', 'color' => 'primary'];
                                $isActive = $section === $tab;
                                $tabLabel = $tab === '_common'
                                    ? 'Basic Info'
                                    : \Illuminate\Support\Str::headline(str_replace('_', ' ', $tab));

                                $hasToggle  = isset($sectionsConfig[$tab]);
                                $tabEnabled = $hasToggle ? ($sectionsConfig[$tab]['enabled'] ?? true) : null;
                            @endphp
                            <div wire:key="nav-{{ $tab }}"
                                 class="cse-nav-item d-flex align-items-center gap-2 px-3 py-2 border-bottom
                                        {{ $isActive ? 'cse-nav-active' : '' }}
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
            </div>
        </div>

        {{-- RIGHT EDIT PANEL --}}
        <div class="col-lg-9">

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
                    $activeLabel = $section === '_common'
                        ? 'Basic Info'
                        : \Illuminate\Support\Str::headline(str_replace('_', ' ', $section ?? ''));
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
                                It will not be visible on your vCard. Toggle it on using the switch above or in the sidebar.
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
    </div>

    <style>
        .cse-nav-item:hover:not(.cse-nav-active) { background: rgba(0,0,0,.04); }
        .cse-nav-active { background: rgba(var(--bs-primary-rgb),.08); border-left: 3px solid var(--bs-primary) !important; }
        .cse-nav-item { border-left: 3px solid transparent; }
    </style>

    <script>
    (function () {
        window.addEventListener('section-changed', function (e) {
            const section = e.detail.section;
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
    </script>
</div>
