<div>
    @section('title', 'vCard Editor')
    <div class="py-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="fs-18 fw-semibold m-0">vCard Content Editor</h4>
                <div class="text-muted small mt-1">{{ $vcard->subdomain }}.{{ $baseDomain }}</div>
            </div>
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Admin</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.vcards.index') }}" class="text-decoration-none">vCards</a></li>
                <li class="breadcrumb-item active">Edit Data</li>
            </ol>
        </div>
    </div>

    @if (!$showIndex)
        <div class="mb-3 text-end">
            <a href="{{ route('admin.vcards.data.section', $vcard->id) }}" class="btn btn-sm btn-light">
                <i class="mdi mdi-arrow-left"></i> Back to Sections
            </a>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($showIndex)
        @php
            $iconMap = [
                'meta' => ['icon' => 'fa-id-card', 'color' => 'text-primary'],
                'assets' => ['icon' => 'fa-image', 'color' => 'text-danger'],
                'banner' => ['icon' => 'fa-flag', 'color' => 'text-warning'],
                'profile' => ['icon' => 'fa-user', 'color' => 'text-info'],
                'sections' => ['icon' => 'fa-layer-group', 'color' => 'text-primary'],
                'labels' => ['icon' => 'fa-tags', 'color' => 'text-success'],
                'status' => ['icon' => 'fa-clock', 'color' => 'text-warning'],
                'services' => ['icon' => 'fa-scissors', 'color' => 'text-primary'],
                'packages' => ['icon' => 'fa-box', 'color' => 'text-info'],
                'products' => ['icon' => 'fa-bag-shopping', 'color' => 'text-success'],
                'gallery' => ['icon' => 'fa-images', 'color' => 'text-danger'],
                'appointment' => ['icon' => 'fa-calendar-check', 'color' => 'text-success'],
                'booking' => ['icon' => 'fa-calendar', 'color' => 'text-success'],
                'hours' => ['icon' => 'fa-clock', 'color' => 'text-warning'],
                'location' => ['icon' => 'fa-location-dot', 'color' => 'text-danger'],
                'social' => ['icon' => 'fa-share-nodes', 'color' => 'text-info'],
                'payments' => ['icon' => 'fa-credit-card', 'color' => 'text-primary'],
                'doctor' => ['icon' => 'fa-stethoscope', 'color' => 'text-danger'],
                'shop' => ['icon' => 'fa-store', 'color' => 'text-success'],
                'barbers' => ['icon' => 'fa-user-group', 'color' => 'text-info'],
                'specializations' => ['icon' => 'fa-star-of-life', 'color' => 'text-danger'],
                'awards' => ['icon' => 'fa-trophy', 'color' => 'text-warning'],
                'testimonials' => ['icon' => 'fa-comment-dots', 'color' => 'text-info'],
            ];
        @endphp
        <div class="row g-4">
            @foreach ($sections as $tab)
                @php
                    $iconData = $iconMap[$tab] ?? ['icon' => 'fa-layer-group', 'color' => 'text-primary'];
                @endphp
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="card h-100 border-0 shadow-sm cursor-pointer section-card" onclick="window.location.href='{{ route('admin.vcards.data.section', [$vcard->id, $tab]) }}'" style="border-radius: 12px; transition: all 0.3s ease;">
                        <div class="card-body text-center py-4">
                            <div class="mb-3">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 64px; height: 64px; background-color: rgba(59, 130, 246, 0.1);">
                                    <i class="fas {{ $iconData['icon'] }} fs-2 {{ $iconData['color'] }}"></i>
                                </div>
                            </div>
                            <h6 class="card-title fw-semibold mb-2">{{ \Illuminate\Support\Str::headline($tab) }}</h6>
                            <p class="card-text text-muted small mb-0">Manage {{ \Illuminate\Support\Str::headline($tab) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <style>
            .section-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
            }
            
            .cursor-pointer {
                cursor: pointer;
            }
        </style>
    @else
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="mb-0 fw-semibold">{{ \Illuminate\Support\Str::headline($section) }}</h5>
                <p class="text-muted small mb-0">Update section content and media</p>
            </div>
            <div class="card-body p-4">
                <form wire:submit.prevent="save">
                    <div class="row">
                        @if (empty($form))
                            <div class="col-12">
                                <div class="alert alert-warning d-flex align-items-center">
                                    <i class="mdi mdi-alert-outline me-2 fs-5"></i>
                                    <span>No data found for this section.</span>
                                </div>
                            </div>
                        @else
                            @foreach ($form as $key => $value)
                                @include('livewire.vcards.partials.field', [
                                    'key' => $key,
                                    'value' => $value,
                                    'wirePath' => $key,
                                ])
                            @endforeach
                        @endif
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex gap-2">
                        <button class="btn btn-primary px-4" type="submit">
                            <span wire:loading.remove><i class="mdi mdi-content-save me-1"></i> Save Changes</span>
                            <span wire:loading><i class="mdi mdi-loading mdi-spin me-1"></i> Saving...</span>
                        </button>
                        <a href="{{ route('admin.vcards.data.section', $vcard->id) }}" class="btn btn-light px-4">
                            <i class="mdi mdi-arrow-left me-1"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <style>
            [data-bs-toggle="collapse"] .mdi-chevron-down {
                transition: transform 0.3s ease;
            }
            [data-bs-toggle="collapse"][aria-expanded="false"] .mdi-chevron-down {
                transform: rotate(0deg);
            }
            [data-bs-toggle="collapse"][aria-expanded="true"] .mdi-chevron-down {
                transform: rotate(180deg);
            }
            .card-header:hover {
                background-color: #e9ecef !important;
            }
        </style>
    @endif
</div>
