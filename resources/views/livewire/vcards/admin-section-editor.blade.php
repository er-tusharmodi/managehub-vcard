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

    @if ($editMode === 'code' && !$showIndex)
        <!-- Code Editor Mode -->
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-semibold">
                            <i class="mdi mdi-code-braces me-2"></i>Code Editor
                        </h5>
                        <p class="text-muted small mb-0">Edit the complete vCard JSON data</p>
                    </div>
                    <button wire:click="switchToVisualEditor" class="btn btn-sm btn-outline-primary">
                        <i class="mdi mdi-eye me-1"></i>Switch to Visual Editor
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="alert alert-info m-3 border-0">
                    <i class="mdi mdi-information-outline me-2"></i>
                    <strong>Caution:</strong> You're editing raw JSON. Invalid JSON will not be saved. Changes affect the entire vCard data structure.
                </div>
                <div class="position-relative">
                    <textarea 
                        wire:model="jsonContent" 
                        class="form-control font-monospace"
                        style="min-height: 600px; border: none; border-radius: 0; font-size: 13px; line-height: 1.6; tab-size: 4;"
                        spellcheck="false"
                    ></textarea>
                </div>
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
                    <a href="{{ route('admin.vcards.data.section', $vcard->id) }}" class="btn btn-light px-4">
                        <i class="mdi mdi-arrow-left me-1"></i>Cancel
                    </a>
                </div>
            </div>
        </div>
    @elseif ($showIndex)
        @if (empty($sectionsConfig))
            <div class="alert alert-info border-0 mb-4">
                <i class="mdi mdi-information-outline me-2"></i>
                <strong>Section configuration not available.</strong>
                <p class="mb-2 mt-2">Content sections will not have enable/disable toggles until synced. All sections are still editable.</p>
                <button onclick="navigator.clipboard.writeText('php artisan vcards:sync-sections')" class="btn btn-sm btn-outline-primary mt-2">
                    <i class="mdi mdi-content-copy me-1"></i>Copy Sync Command
                </button>
            </div>
        @endif
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
                    
                    // Check if this section has config (content sections have toggles)
                    $hasConfig = !empty($sectionsConfig) && isset($sectionsConfig[$tab]);
                    $isEnabled = $hasConfig ? ($sectionsConfig[$tab]['enabled'] ?? true) : true;
                    $sectionLabel = $hasConfig ? ($sectionsConfig[$tab]['label'] ?? \Illuminate\Support\Str::headline($tab)) : \Illuminate\Support\Str::headline($tab);
                @endphp
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="card h-100 border-0 shadow-sm {{ $isEnabled ? '' : 'opacity-75' }}" style="border-radius: 12px; transition: all 0.3s ease;">
                        <div class="card-body py-4">
                            <!-- Header with toggle (only for content sections with config) -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background-color: rgba(59, 130, 246, 0.1);">
                                    <i class="fas {{ $iconData['icon'] }} fs-4 {{ $iconData['color'] }}"></i>
                                </div>
                                @if ($hasConfig)
                                    <div class="form-check form-switch">
                                        <input 
                                            class="form-check-input" 
                                            type="checkbox" 
                                            id="toggle-{{ $tab }}"
                                            {{ $isEnabled ? 'checked' : '' }}
                                            wire:click="toggleSection('{{ $tab }}')"
                                            style="cursor: pointer; width: 2.5rem; height: 1.25rem;"
                                            title="Toggle section visibility"
                                        >
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Section title and status -->
                            <div class="text-center mb-3">
                                <h6 class="card-title fw-semibold mb-2">{{ $sectionLabel }}</h6>
                                @if ($hasConfig)
                                    @if ($isEnabled)
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="mdi mdi-check-circle me-1"></i>Enabled
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">
                                            <i class="mdi mdi-close-circle me-1"></i>Disabled
                                        </span>
                                    @endif
                                @else
                                    <span class="badge bg-light text-muted">
                                        <i class="mdi mdi-cog me-1"></i>System Section
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Edit button -->
                            <div class="text-center">
                                @if ($isEnabled)
                                    <a href="{{ route('admin.vcards.data.section', [$vcard->id, $tab]) }}" 
                                       class="btn btn-sm btn-primary w-100">
                                        <i class="mdi mdi-pencil me-1"></i>Edit Section
                                    </a>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary w-100" disabled>
                                        <i class="mdi mdi-pencil-off me-1"></i>Section Disabled
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Code Editor Button -->
        <div class="mt-4 text-end">
            <button wire:click="switchToCodeEditor" class="btn btn-outline-dark">
                <i class="mdi mdi-code-braces me-1"></i>Switch to Code Editor
            </button>
        </div>
    @else
        @php
            $sectionLabel = \Illuminate\Support\Str::headline($section);
            $itemLabel = \Illuminate\Support\Str::singular($sectionLabel);
            $isFormList = is_array($form) && !empty($form) && array_values($form) === $form;
        @endphp
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="mb-0 fw-semibold">{{ $sectionLabel }}</h5>
                        <p class="text-muted small mb-0">Update section content and media</p>
                    </div>
                    @if ($isFormList)
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="mdi mdi-plus me-1"></i> Add {{ $itemLabel }}
                        </button>
                    @endif
                </div>
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
                            @if($isFormList)
                                <!-- Render as list/table if form itself is a sequential array -->
                                    @include('livewire.vcards.partials.field', [
                                        'key' => $section,
                                        'value' => $form,
                                        'wirePath' => '',
                                        'categoryOptions' => $categoryOptions,
                                        'hideListHeaderButton' => true,
                                    ])
                            @else
                                <!-- Render individual fields if form is associative -->
                                @foreach ($form as $key => $value)
                                        @include('livewire.vcards.partials.field', [
                                            'key' => $key,
                                            'value' => $value,
                                            'wirePath' => $key,
                                            'categoryOptions' => $categoryOptions,
                                        ])
                                @endforeach
                            @endif
                        @endif
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex gap-2 justify-content-between">
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary px-4" type="submit">
                                <span wire:loading.remove><i class="mdi mdi-content-save me-1"></i> Save Changes</span>
                                <span wire:loading><i class="mdi mdi-loading mdi-spin me-1"></i> Saving...</span>
                            </button>
                            <a href="{{ route('admin.vcards.data.section', $vcard->id) }}" class="btn btn-light px-4">
                                <i class="mdi mdi-arrow-left me-1"></i> Cancel
                            </a>
                        </div>
                        <button wire:click="switchToCodeEditor" type="button" class="btn btn-outline-dark">
                            <i class="mdi mdi-code-braces me-1"></i>Switch to Code Editor
                        </button>
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
