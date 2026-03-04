{{--
 | bookshop-template/sections.blade.php
 | All front-end sections configuration for bookshop-template.
 | Each entry is a sub-section with its own fields.
 | Available: $form (array), $categoryOptions (array)
--}}

@php
    $subSections = [
        'categories' => ['label' => 'Categories Bar', 'icon' => 'mdi-format-list-bulleted-type', 'color' => 'primary'],
        'location'   => ['label' => 'Location Block', 'icon' => 'mdi-map-marker-outline', 'color' => 'danger'],
        'social'     => ['label' => 'Social Links Block', 'icon' => 'mdi-share-variant-outline', 'color' => 'info'],
        'services'   => ['label' => 'Services Block', 'icon' => 'mdi-briefcase-outline', 'color' => 'primary'],
        'products'   => ['label' => 'Products Section Header', 'icon' => 'mdi-book-open-variant', 'color' => 'success'],
        'gallery'    => ['label' => 'Gallery Block', 'icon' => 'mdi-image-multiple-outline', 'color' => 'warning'],
        'hours'      => ['label' => 'Business Hours Block', 'icon' => 'mdi-clock-outline', 'color' => 'warning'],
        'qr'         => ['label' => 'QR Code Block', 'icon' => 'mdi-qrcode', 'color' => 'dark'],
        'payments'   => ['label' => 'Payment Methods Block', 'icon' => 'mdi-credit-card-outline', 'color' => 'primary'],
        'contact'    => ['label' => 'Contact Form Block', 'icon' => 'mdi-email-outline', 'color' => 'primary'],
    ];
@endphp

@foreach($subSections as $subKey => $subMeta)
    @if(isset($form[$subKey]))
        @php $subData = $form[$subKey]; @endphp
        <div class="col-12 mb-4">
            <div class="border rounded-3 p-3" style="background:#f8fafc;">
                <div class="fw-semibold mb-3 d-flex align-items-center gap-2" style="font-size:.9rem;">
                    <span class="avatar-xs flex-shrink-0">
                        <span class="avatar-title rounded-circle bg-soft-{{ $subMeta['color'] }} text-{{ $subMeta['color'] }}" style="font-size:14px;">
                            <i class="mdi {{ $subMeta['icon'] }}"></i>
                        </span>
                    </span>
                    {{ $subMeta['label'] }}
                </div>
                <div class="row g-2">
                    @foreach($subData as $fieldKey => $fieldValue)
                        @if(is_array($fieldValue))
                            {{-- Delegate nested arrays/lists to generic renderer --}}
                            @include('livewire.vcards.partials.field', [
                                'key'             => $fieldKey,
                                'value'           => $fieldValue,
                                'wirePath'        => $subKey . '.' . $fieldKey,
                                'categoryOptions' => $categoryOptions ?? [],
                            ])
                        @else
                            @php
                                $isTextarea = preg_match('/(description|desc|about|bio|note|message|text)/i', $fieldKey);
                                $colClass = $isTextarea ? 'col-12' : 'col-lg-6';
                                $fLabel = \Illuminate\Support\Str::headline($fieldKey);
                                $wPath = $subKey . '.' . $fieldKey;
                            @endphp
                            <div class="{{ $colClass }} mb-2">
                                <label class="form-label fw-semibold mb-1" style="font-size:.85rem;">{{ $fLabel }}</label>
                                @if($isTextarea)
                                    <textarea class="form-control form-control-sm @error('form.' . $wPath) is-invalid @enderror"
                                              wire:model="form.{{ $wPath }}"
                                              rows="2"></textarea>
                                @else
                                    <input type="text"
                                           class="form-control form-control-sm @error('form.' . $wPath) is-invalid @enderror"
                                           wire:model="form.{{ $wPath }}">
                                @endif
                                @error('form.' . $wPath)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endforeach
