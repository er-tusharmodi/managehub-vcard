<div>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Visual Editor: {{ $templateName }}</h4>
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

        @if ($showIndex)
            <!-- Section Index View -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h5 class="card-title mb-1">Select a Section to Edit</h5>
                                    <p class="text-muted mb-0">Edit template default data structure</p>
                                </div>
                                <a href="{{ route('admin.templates.edit.code', $templateKey) }}" class="btn btn-outline-primary">
                                    <i class="mdi mdi-code-braces me-1"></i> Code Editor
                                </a>
                            </div>

                            @php
                                $iconMap = [
                                    'meta' => ['icon' => 'mdi-information', 'color' => 'primary'],
                                    'assets' => ['icon' => 'mdi-image', 'color' => 'danger'],
                                    'banner' => ['icon' => 'mdi-panorama', 'color' => 'warning'],
                                    'profile' => ['icon' => 'mdi-account', 'color' => 'info'],
                                    'sections' => ['icon' => 'mdi-layers', 'color' => 'primary'],
                                    'labels' => ['icon' => 'mdi-tag-multiple', 'color' => 'success'],
                                    'status' => ['icon' => 'mdi-clock', 'color' => 'warning'],
                                    'services' => ['icon' => 'mdi-briefcase', 'color' => 'primary'],
                                    'packages' => ['icon' => 'mdi-package', 'color' => 'info'],
                                    'products' => ['icon' => 'mdi-shopping', 'color' => 'success'],
                                    'gallery' => ['icon' => 'mdi-image-multiple', 'color' => 'danger'],
                                    'appointment' => ['icon' => 'mdi-calendar-check', 'color' => 'success'],
                                    'booking' => ['icon' => 'mdi-calendar', 'color' => 'success'],
                                    'hours' => ['icon' => 'mdi-clock-outline', 'color' => 'warning'],
                                    'location' => ['icon' => 'mdi-map-marker', 'color' => 'danger'],
                                    'social' => ['icon' => 'mdi-share-variant', 'color' => 'info'],
                                    'payments' => ['icon' => 'mdi-credit-card', 'color' => 'primary'],
                                    'doctor' => ['icon' => 'mdi-hospital-box', 'color' => 'danger'],
                                    'shop' => ['icon' => 'mdi-store', 'color' => 'success'],
                                    'barbers' => ['icon' => 'mdi-people', 'color' => 'info'],
                                    'specializations' => ['icon' => 'mdi-star', 'color' => 'danger'],
                                    'awards' => ['icon' => 'mdi-trophy', 'color' => 'warning'],
                                    'testimonials' => ['icon' => 'mdi-comment-multiple', 'color' => 'info'],
                                ];
                            @endphp

                            <div class="row g-3">
                                @foreach ($sections as $tab)
                                    @php
                                        $iconData = $iconMap[$tab] ?? ['icon' => 'mdi-layers', 'color' => 'primary'];
                                    @endphp
                                    <div class="col-md-6 col-lg-3">
                                        <a href="{{ route('admin.templates.edit.visual', ['templateKey' => $templateKey, 'section' => $tab]) }}" class="text-decoration-none">
                                            <div class="card border border-{{ $iconData['color'] }} card-hover h-100">
                                                <div class="card-body text-center">
                                                    <div class="avatar-sm mx-auto mb-3">
                                                        <div class="avatar-title rounded-circle bg-soft-{{ $iconData['color'] }} text-{{ $iconData['color'] }} font-size-24">
                                                            <i class="mdi {{ $iconData['icon'] }}"></i>
                                                        </div>
                                                    </div>
                                                    <h6 class="mb-1">{{ str_replace('_', ' ', ucfirst($tab)) }}</h6>
                                                    <p class="text-muted font-size-13 mb-0">Edit {{ str_replace('_', ' ', $tab) }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <style>
                .card-hover {
                    transition: all 0.3s ease;
                }
                .card-hover:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
                }
            </style>
        @else
            <!-- Section Edit View -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0">
                                        <i class="mdi mdi-pencil me-2 text-primary"></i>{{ str_replace('_', ' ', ucfirst($section)) }}
                                    </h5>
                                    <small class="text-muted">Edit default template data for this section</small>
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.templates.edit.visual', $templateKey) }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="mdi mdi-arrow-left"></i> Back to Sections
                                    </a>
                                    <a href="{{ route('admin.templates.edit.code', $templateKey) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="mdi mdi-code-braces"></i> Code Editor
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show">
                                    <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <i class="mdi mdi-alert-circle me-2"></i>{{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show">
                                    <i class="mdi mdi-alert-circle me-2"></i>
                                    <strong>Please fix the following errors:</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form wire:submit.prevent="save">
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
                                        @endphp
                                        
                                        @php
                                            $assetBaseUrl = url("template-assets/{$templateKey}/");
                                        @endphp

                                        @if($isFormList)
                                            @include('livewire.vcards.partials.field', [
                                                'key' => $section,
                                                'value' => $form,
                                                'wirePath' => '',
                                                'assetBaseUrl' => $assetBaseUrl,
                                            ])
                                        @else
                                            @foreach ($form as $key => $value)
                                                @include('livewire.vcards.partials.field', [
                                                    'key' => $key,
                                                    'value' => $value,
                                                    'wirePath' => $key,
                                                    'assetBaseUrl' => $assetBaseUrl,
                                                ])
                                            @endforeach
                                        @endif
                                    @endif
                                </div>

                                <div class="mt-4 d-flex gap-2 border-top pt-4">
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
                                            <small>You have uploaded files. Click "Save Changes" to apply them.</small>
                                        </div>
                                    @endif
                                    <div class="ms-auto d-flex gap-2">
                                        <a href="{{ route('admin.templates.edit.visual', $templateKey) }}" class="btn btn-outline-secondary">
                                            <i class="mdi mdi-arrow-left me-1"></i>Back to Sections
                                        </a>
                                        <a href="{{ route('admin.templates.index') }}" class="btn btn-outline-secondary">
                                            <i class="mdi mdi-home-outline me-1"></i>All Templates
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
