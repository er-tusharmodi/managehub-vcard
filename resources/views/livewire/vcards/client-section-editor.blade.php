<div>
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">vCard Editor</h4>
            <div class="text-muted">{{ $vcard->subdomain }}.{{ $baseDomain }}</div>
        </div>

        <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
                <li class="breadcrumb-item"><a href="/">vCard</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($subscriptionBlocked)
        <div class="alert alert-warning">
            {{ $subscriptionMessage }}
        </div>
    @elseif ($showIndex)
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
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0 hover-shadow cursor-pointer" onclick="window.location.href='{{ route('vcard.editor.section', ['subdomain' => $vcard->subdomain, 'section' => $tab]) }}'">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas {{ $iconData['icon'] }} fs-1 {{ $iconData['color'] }}"></i>
                            </div>
                            <h5 class="card-title">{{ \Illuminate\Support\Str::headline($tab) }}</h5>
                            <p class="card-text text-muted">Manage {{ \Illuminate\Support\Str::headline($tab) }} fields</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <style>
            .hover-shadow {
                transition: all 0.3s ease;
            }
            .hover-shadow:hover {
                box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
                transform: translateY(-2px);
            }
            .cursor-pointer {
                cursor: pointer;
            }
        </style>
    @else
        <div class="card">
            <div class="card-body">
                <form wire:submit.prevent="save">
                    <div class="row">
                        @if (empty($form))
                            <div class="col-12">
                                <div class="alert alert-warning">No data found for this section.</div>
                            </div>
                        @else
                            @php
                                // Check if the entire form is a list (array of objects)
                                $isFormList = is_array($form) && !empty($form) && array_values($form) === $form;
                            @endphp
                            
                            @if($isFormList)
                                <!-- Render as list/table if form itself is a sequential array -->
                                @include('livewire.vcards.partials.field', [
                                    'key' => $section,
                                    'value' => $form,
                                    'wirePath' => '',
                                ])
                            @else
                                <!-- Render individual fields if form is associative -->
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

                    <div class="mt-3 d-flex gap-2">
                        <button class="btn btn-primary" type="submit">
                            <span wire:loading.remove>Save Changes</span>
                            <span wire:loading><i class="mdi mdi-loading mdi-spin"></i> Saving...</span>
                        </button>
                        <a href="{{ route('vcard.editor.section', ['subdomain' => $vcard->subdomain]) }}" class="btn btn-secondary">Back</a>
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
