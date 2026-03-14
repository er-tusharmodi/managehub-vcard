{{-- restaurant-cafe-template/location.blade.php — {name, address, mapLabel, transport:[{icon,label,value,stroke}]} --}}

@php
$transportMeta = [
    'metro'    => ['emoji' => '🚇', 'label' => 'Metro',    'color' => '#1565c0'],
    'bus'      => ['emoji' => '🚌', 'label' => 'Bus',      'color' => '#e65100'],
    'train'    => ['emoji' => '🚆', 'label' => 'Train',    'color' => '#5c35cc'],
    'taxi'     => ['emoji' => '🚕', 'label' => 'Taxi',     'color' => '#f9a825'],
    'auto'     => ['emoji' => '🛺', 'label' => 'Auto',     'color' => '#00695c'],
    'bike'     => ['emoji' => '🏍️', 'label' => 'Bike',     'color' => '#4e342e'],
    'car'      => ['emoji' => '🚗', 'label' => 'Car',      'color' => '#1976d2'],
    'rickshaw' => ['emoji' => '🛺', 'label' => 'Rickshaw', 'color' => '#bf360c'],
    'walk'     => ['emoji' => '🚶', 'label' => 'Walk',     'color' => '#2e7d32'],
    'parking'  => ['emoji' => '🅿️', 'label' => 'Parking',  'color' => '#37474f'],
    'delivery' => ['emoji' => '🛵', 'label' => 'Delivery', 'color' => '#6a1b9a'],
    'flight'   => ['emoji' => '✈️', 'label' => 'Airport',  'color' => '#0288d1'],
];

$transportSvgs = [
    'metro'    => '<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
    'bus'      => '<rect x="2" y="4" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/><circle cx="7" cy="19" r="1.5"/><circle cx="17" cy="19" r="1.5"/><path d="M7 4V2m10 2V2"/>',
    'train'    => '<rect x="5" y="2" width="14" height="14" rx="3"/><line x1="9" y1="2" x2="9" y2="8"/><line x1="15" y1="2" x2="15" y2="8"/><line x1="9" y1="16" x2="7" y2="22"/><line x1="15" y1="16" x2="17" y2="22"/><line x1="6" y1="22" x2="18" y2="22"/>',
    'taxi'     => '<rect x="2" y="8" width="20" height="11" rx="2"/><path d="M7 8V6a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2"/><line x1="2" y1="13" x2="22" y2="13"/><circle cx="7" cy="20" r="1.5"/><circle cx="17" cy="20" r="1.5"/>',
    'auto'     => '<path d="M2 14h14V9L13 5H2v9z"/><circle cx="6" cy="18" r="2"/><circle cx="16" cy="18" r="2"/><path d="M16 10v4m0-4h4l2 3v1h-6"/>',
    'bike'     => '<circle cx="5" cy="17" r="3"/><circle cx="19" cy="17" r="3"/><path d="M5 17l3-7h5l3 7"/><path d="M12 10l-2-6H7"/><path d="M19 14l3-2"/>',
    'car'      => '<path d="M7 11l2-5h6l2 5"/><path d="M3 13h18v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4z"/><circle cx="7" cy="19" r="1.5"/><circle cx="17" cy="19" r="1.5"/>',
    'rickshaw' => '<circle cx="6" cy="17" r="3"/><circle cx="18" cy="17" r="3"/><path d="M6 17V9h10l2 5v3"/><path d="M16 9V6l-3-2H11"/>',
    'walk'     => '<circle cx="12" cy="4" r="2"/><path d="M9 22l1.5-5L13 19l2.5-4L18 22"/><path d="M10 11l-2 4m4-7l3 4"/><path d="M10 11c0 0 1-4 2-4s3 4 3 4"/>',
    'parking'  => '<rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/>',
    'delivery' => '<circle cx="12" cy="12" r="10"/><path d="M8.56 2.75c4.37 6.03 6.02 9.42 8.03 17.72m2.54-15.38c-3.72 4.35-8.94 5.66-16.88 5.85m19.5 1.9c-3.5-.93-6.63-.82-8.94 0-2.58.92-5.01 2.86-7.44 6.32"/>',
    'flight'   => '<line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>',
];
@endphp

{{-- ── Section header ────────────────────────────────────────────────────── --}}
<div class="col-12 mb-3">
    <div class="d-flex align-items-center gap-2 pb-2 border-bottom">
        <span class="avatar-xs flex-shrink-0">
            <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-18">
                <i class="mdi mdi-map-marker"></i>
            </span>
        </span>
        <div>
            <h6 class="mb-0 fw-semibold">Location Details</h6>
            <small class="text-muted">Restaurant address and how customers find you</small>
        </div>
    </div>
</div>

{{-- ── Address Fields ─────────────────────────────────────────────────────── --}}
<div class="col-12">
    <div class="card border-0 shadow-sm mb-3" style="border-radius:.85rem;">
        <div class="card-body p-3">
            <p class="fw-semibold text-uppercase text-muted mb-3" style="font-size:.68rem;letter-spacing:.08em;">
                <i class="mdi mdi-store-outline me-1"></i>Address Info
            </p>
            <div class="row g-3">
                {{-- Name --}}
                <div class="col-sm-6">
                    <label class="form-label small fw-semibold mb-1">Location Name</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="mdi mdi-store-outline text-muted"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0"
                               wire:model="form.name"
                               placeholder="e.g. La Cucina Italiana">
                    </div>
                </div>
                {{-- Map Button Label --}}
                <div class="col-sm-6">
                    <label class="form-label small fw-semibold mb-1">Directions Button Text</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="mdi mdi-directions text-muted"></i></span>
                        <input type="text" class="form-control border-start-0 ps-0"
                               wire:model="form.mapLabel"
                               placeholder="Get Directions">
                    </div>
                    <small class="text-muted d-block mt-1"><i class="mdi mdi-information-outline me-1"></i>Text shown on the map button</small>
                </div>
                {{-- Full Address --}}
                <div class="col-12">
                    <label class="form-label small fw-semibold mb-1">Full Address</label>
                    <textarea class="form-control form-control-sm" rows="2"
                              wire:model="form.address"
                              placeholder="12, Connaught Place, Block B, New Delhi – 110001"></textarea>
                    <small class="text-muted d-block mt-1"><i class="mdi mdi-map-marker-outline me-1"></i>Displayed on the vCard below the location name</small>
                </div>
            </div>
            {{-- Tip: Maps URL is in general info --}}
            <div class="mt-3 p-2 rounded-2 d-flex align-items-start gap-2" style="background:#eff6ff;border:1px solid #bfdbfe;">
                <i class="mdi mdi-google-maps text-primary flex-shrink-0 mt-1" style="font-size:1rem;"></i>
                <small class="text-primary-emphasis">
                    <strong>Tip:</strong> The Google Maps link (used when clicking "Get Directions") is set in the <strong>General Info</strong> tab under <em>Google Maps URL</em>.
                </small>
            </div>
        </div>
    </div>
</div>

{{-- ── Transport / How to Get Here ──────────────────────────────────────────── --}}
<div class="col-12">
    <div class="card border-0 shadow-sm" style="border-radius:.85rem;">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <div>
                    <p class="fw-semibold text-uppercase text-muted mb-0" style="font-size:.68rem;letter-spacing:.08em;">
                        <i class="mdi mdi-bus-stop me-1"></i>How to Get Here
                    </p>
                    <small class="text-muted">Show customers the best ways to reach you</small>
                </div>
                <button type="button" class="btn btn-sm btn-primary px-3"
                        wire:click="addRowAndSave('transport', ['icon','label','value','stroke'])">
                    <i class="mdi mdi-plus me-1"></i>Add
                </button>
            </div>

            @if(count($form['transport'] ?? []) > 0)
            <div class="mt-3 d-flex flex-column gap-2">
            @foreach(($form['transport'] ?? []) as $ti => $tr)
            @php $tMeta = $transportMeta[$tr['icon'] ?? 'metro'] ?? $transportMeta['metro']; @endphp
            <div class="rounded-3 p-3" wire:key="rc-trans-{{ $ti }}"
                 x-data="{}"
                 style="background:#f8fafc;border:1px solid #e2e8f0;border-left:4px solid {{ $tr['stroke'] ?? $tMeta['color'] }} !important;">

                {{-- Transport Type Icon Picker + Delete --}}
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label small fw-semibold mb-0">Transport Type</label>
                    <button type="button"
                            class="btn btn-sm btn-outline-danger p-0 rounded-circle"
                            style="width:28px;height:28px;flex-shrink:0;"
                            x-on:click="showConfirmToast('Remove this transport option?', () => $wire.removeRowWithConfirm({{ $ti }}, 'transport'))">
                        <i class="mdi mdi-delete-outline" style="font-size:13px;"></i>
                    </button>
                </div>
                <div class="d-flex flex-wrap gap-2 mb-2">
                    @foreach($transportMeta as $tKey => $tInfo)
                        @php $isActive = ($tr['icon'] ?? '') === $tKey; @endphp
                        <button type="button"
                                wire:click="$set('form.transport.{{ $ti }}.icon', '{{ $tKey }}')"
                                title="{{ $tInfo['label'] }}"
                                style="width:54px;height:54px;border-radius:10px;flex-direction:column;display:flex;align-items:center;justify-content:center;gap:3px;padding:4px;cursor:pointer;border:2px solid {{ $isActive ? $tInfo['color'] : '#e2e8f0' }};background:{{ $isActive ? $tInfo['color'] : '#fff' }};color:{{ $isActive ? '#fff' : $tInfo['color'] }};">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $transportSvgs[$tKey] ?? '' !!}</svg>
                            <span style="font-size:8px;line-height:1.1;font-weight:600;white-space:nowrap;overflow:hidden;max-width:48px;text-overflow:ellipsis;color:inherit;">{{ $tInfo['label'] }}</span>
                        </button>
                    @endforeach
                </div>

                {{-- Row 2: Short label + Description + Color preset --}}
                <div class="row g-2 align-items-end">
                    <div class="col-sm-4">
                        <label class="form-label small fw-semibold mb-1">Short Label</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.transport.{{ $ti }}.label"
                               placeholder="{{ $tMeta['label'] }}">
                        <small class="text-muted">Shown as title</small>
                    </div>
                    <div class="col-sm-5">
                        <label class="form-label small fw-semibold mb-1">Description / Info</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.transport.{{ $ti }}.value"
                               placeholder="e.g. Rajiv Chowk · 3 min walk">
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small fw-semibold mb-1">Badge Color</label>
                        <div class="d-flex align-items-center gap-1 flex-wrap">
                            @foreach(['#1565c0','#e65100','#2e7d32','#f9a825','#6a1b9a','#00695c','#37474f','#c62828'] as $pc)
                            <button type="button"
                                    wire:click="$set('form.transport.{{ $ti }}.stroke', '{{ $pc }}')"
                                    class="p-0 border-0 rounded-circle flex-shrink-0"
                                    title="{{ $pc }}"
                                    style="width:20px;height:20px;background:{{ $pc }};outline:{{ ($tr['stroke'] ?? '') === $pc ? '2px solid #0ea5e9' : 'none' }};outline-offset:2px;cursor:pointer;"></button>
                            @endforeach
                            <input type="color"
                                   wire:model.lazy="form.transport.{{ $ti }}.stroke"
                                   class="p-0 border rounded flex-shrink-0"
                                   title="Custom color"
                                   style="width:20px;height:20px;cursor:pointer;">
                        </div>
                    </div>
                </div>

            </div>
            @endforeach
            </div>
            @else
            <div class="text-center py-4 mt-2 rounded-3" style="border:2px dashed #cbd5e1;background:#f8fafc;">
                <span class="d-block mb-2" style="font-size:2rem;">🗺️</span>
                <p class="text-muted small mb-1 fw-semibold">No transport options added yet</p>
                <p class="text-muted" style="font-size:.75rem;">Tell customers how to reach you — Metro, Bus, Parking, etc.</p>
            </div>
            @endif
        </div>
    </div>
</div>

