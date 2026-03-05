{{--
 | bookshop-template/sections.blade.php
 | Sub-sections rendered as individual pages via Alpine.js tab navigation.
 | Available: $form (array), $categoryOptions (array)
--}}

@php
    $subSections = [
        'categories' => ['label' => 'Categories Bar',      'icon' => 'mdi-format-list-bulleted-type', 'color' => 'primary'],
        'location'   => ['label' => 'Location Block',      'icon' => 'mdi-map-marker-outline',        'color' => 'danger'],
        'social'     => ['label' => 'Social Links',        'icon' => 'mdi-share-variant-outline',     'color' => 'info'],
        'services'   => ['label' => 'Services Block',      'icon' => 'mdi-briefcase-outline',         'color' => 'primary'],
        'products'   => ['label' => 'Products Header',     'icon' => 'mdi-book-open-variant',         'color' => 'success'],
        'gallery'    => ['label' => 'Gallery Block',       'icon' => 'mdi-image-multiple-outline',    'color' => 'warning'],
        'hours'      => ['label' => 'Business Hours',      'icon' => 'mdi-clock-outline',             'color' => 'warning'],
        'qr'         => ['label' => 'QR Code Block',       'icon' => 'mdi-qrcode',                   'color' => 'dark'],
        'payments'   => ['label' => 'Payment Methods',     'icon' => 'mdi-credit-card-outline',       'color' => 'primary'],
        'contact'    => ['label' => 'Contact Form',        'icon' => 'mdi-email-outline',             'color' => 'primary'],
    ];
    $availableKeys = array_keys(array_filter($subSections, fn($k) => isset($form[$k]), ARRAY_FILTER_USE_KEY));
    $firstKey = $availableKeys[0] ?? null;
@endphp

@if($firstKey)
<div class="col-12"
     x-data="{
         activeSubSection: (window.location.hash && window.location.hash.length > 1) ? window.location.hash.slice(1) : '{{ $firstKey }}',
         setTab(key) {
             this.activeSubSection = key;
             history.replaceState(null, '', window.location.pathname + window.location.search + '#' + key);
         }
     }"
     x-init="window.addEventListener('hashchange', () => {
         if (window.location.hash && window.location.hash.length > 1) {
             activeSubSection = window.location.hash.slice(1);
         }
     })">

    {{-- Tab Bar --}}
    <div class="d-flex flex-wrap gap-2 mb-4 pb-2 border-bottom">
        @foreach($subSections as $subKey => $subMeta)
            @if(isset($form[$subKey]))
                <button type="button"
                        class="btn btn-sm d-flex align-items-center gap-1"
                        :class="activeSubSection === '{{ $subKey }}'
                            ? 'btn-{{ $subMeta['color'] }}'
                            : 'btn-outline-secondary text-muted'"
                        x-on:click="setTab('{{ $subKey }}')">
                    <i class="mdi {{ $subMeta['icon'] }}" style="font-size:15px;"></i>
                    {{ $subMeta['label'] }}
                </button>
            @endif
        @endforeach
    </div>

    {{-- Pages --}}
    @foreach($subSections as $subKey => $subMeta)
        @if(isset($form[$subKey]))
            @php $subData = $form[$subKey]; @endphp
            <div x-show="activeSubSection === '{{ $subKey }}'" x-cloak>

                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="avatar-xs flex-shrink-0">
                        <span class="avatar-title rounded-circle bg-soft-{{ $subMeta['color'] }} text-{{ $subMeta['color'] }}" style="font-size:15px;">
                            <i class="mdi {{ $subMeta['icon'] }}"></i>
                        </span>
                    </span>
                    <span class="fw-semibold" style="font-size:.95rem;">{{ $subMeta['label'] }}</span>
                </div>

                <div class="row g-2">
                    @foreach($subData as $fieldKey => $fieldValue)
                        @if(is_array($fieldValue))
                            @include('livewire.vcards.partials.field', [
                                'key'             => $fieldKey,
                                'value'           => $fieldValue,
                                'wirePath'        => $subKey . '.' . $fieldKey,
                                'categoryOptions' => $categoryOptions ?? [],
                            ])
                        @else
                            @php
                                $staticFields = ['title','submit','successaction','badge','suggest','download','copy','mapbutton','maplabel','confirmlabel','successbutton','heading','todaylabel','suggestlabel','downloadlabel','copylabel','helptext','noitems','noresults','todaybadge','enquirebutton','verifiedlabel'];
                                if (in_array(strtolower($fieldKey), $staticFields) || preg_match('/label$|btn$|heading$/i', $fieldKey)) { continue; }
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
        @endif
    @endforeach

</div>
@endif
