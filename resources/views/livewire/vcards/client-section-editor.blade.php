<div>
    @if ($subscriptionBlocked)
        <div class="alert alert-warning border-0 shadow-sm">
            <div class="d-flex align-items-start gap-2">
                <i class="mdi mdi-alert fs-18 mt-1 flex-shrink-0"></i>
                <div>
                    <strong>Subscription Inactive</strong>
                    <p class="mb-0 small">{{ $subscriptionMessage }}</p>
                </div>
            </div>
        </div>
    @elseif ($showIndex)
        <!-- Section Index View -->
        <div class="mb-4">
            <p class="text-muted">Select a section to edit your vCard content</p>
        </div>

        @php
            $iconMap = [
                'meta' => ['icon' => 'mdi-information', 'color' => 'text-primary'],
                'assets' => ['icon' => 'mdi-image', 'color' => 'text-danger'],
                'banner' => ['icon' => 'mdi-panorama', 'color' => 'text-warning'],
                'profile' => ['icon' => 'mdi-account', 'color' => 'text-info'],
                'sections' => ['icon' => 'mdi-layers', 'color' => 'text-primary'],
                'labels' => ['icon' => 'mdi-tag-multiple', 'color' => 'text-success'],
                'status' => ['icon' => 'mdi-clock', 'color' => 'text-warning'],
                'services' => ['icon' => 'mdi-briefcase', 'color' => 'text-primary'],
                'packages' => ['icon' => 'mdi-package', 'color' => 'text-info'],
                'products' => ['icon' => 'mdi-shopping', 'color' => 'text-success'],
                'gallery' => ['icon' => 'mdi-image-multiple', 'color' => 'text-danger'],
                'appointment' => ['icon' => 'mdi-calendar-check', 'color' => 'text-success'],
                'booking' => ['icon' => 'mdi-calendar', 'color' => 'text-success'],
                'hours' => ['icon' => 'mdi-clock-outline', 'color' => 'text-warning'],
                'location' => ['icon' => 'mdi-map-marker', 'color' => 'text-danger'],
                'social' => ['icon' => 'mdi-share-variant', 'color' => 'text-info'],
                'payments' => ['icon' => 'mdi-credit-card', 'color' => 'text-primary'],
                'doctor' => ['icon' => 'mdi-hospital-box', 'color' => 'text-danger'],
                'shop' => ['icon' => 'mdi-store', 'color' => 'text-success'],
                'barbers' => ['icon' => 'mdi-people', 'color' => 'text-info'],
                'specializations' => ['icon' => 'mdi-star', 'color' => 'text-danger'],
                'awards' => ['icon' => 'mdi-trophy', 'color' => 'text-warning'],
                'testimonials' => ['icon' => 'mdi-comment-multiple', 'color' => 'text-info'],
            ];
        @endphp

        <div class="row g-3">
            @foreach ($sections as $tab)
                @php
                    $iconData = $iconMap[$tab] ?? ['icon' => 'mdi-layers', 'color' => 'text-primary'];
                @endphp
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('vcard.editor', ['subdomain' => $vcard->subdomain, 'section' => $tab]) }}" class="text-decoration-none text-reset h-100">
                        <div class="card border-0 shadow-sm h-100 transition-all-3s" style="cursor: pointer;">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="mdi {{ $iconData['icon'] }} fs-1 {{ $iconData['color'] }}"></i>
                                </div>
                                <h6 class="card-title fw-semibold">{{ str_replace('_', ' ', ucfirst($tab)) }}</h6>
                                <small class="text-muted">Edit {{ str_replace('_', ' ', $tab) }}</small>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <style>
            .transition-all-3s {
                transition: all 0.3s ease;
            }
            .card:hover {
                transform: translateY(-2px);
                box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
            }
        </style>
    @else
        <!-- Section Edit View -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-semibold">
                            <i class="mdi mdi-pencil me-2 text-primary"></i>{{ str_replace('_', ' ', ucfirst($section)) }}
                        </h5>
                        <small class="text-muted">Edit fields for this section</small>
                    </div>
                    <a href="{{ route('vcard.editor', ['subdomain' => $vcard->subdomain]) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="mdi mdi-home-outline"></i> Sections
                    </a>
                </div>
            </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0" role="alert">
                        <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form wire:submit.prevent="save">
                    <div class="row">
                        @if (empty($form))
                            <div class="col-12">
                                <div class="alert alert-info border-0">
                                    <i class="mdi mdi-information-outline me-2"></i>No data available for this section.
                                </div>
                            </div>
                        @else
                            @php
                                $isFormList = is_array($form) && !empty($form) && array_values($form) === $form;
                            @endphp
                            
                            @if($isFormList)
                                @include('livewire.vcards.partials.field', [
                                    'key' => $section,
                                    'value' => $form,
                                    'wirePath' => '',
                                ])
                            @else
                                @foreach ($form as $key => $value)
                                    @include('livewire.vcards.partials.field', [
                                        'key' => $key,
                                        'value' => $value,
                                        'wirePath' => $key,
                                    ])
                                @endforeach
                            @endif
                        @endif
                    </div>

                    <div class="mt-4 d-flex gap-2 border-top pt-4">
                        <button class="btn btn-primary" type="submit">
                            <span wire:loading.remove>
                                <i class="mdi mdi-content-save me-1"></i>Save Changes
                            </span>
                            <span wire:loading>
                                <i class="mdi mdi-loading mdi-spin me-1"></i>Saving...
                            </span>
                        </button>
                        <a href="{{ route('vcard.editor', ['subdomain' => $vcard->subdomain]) }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-arrow-left me-1"></i>Back
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
        </style>
    @endif
</div>
