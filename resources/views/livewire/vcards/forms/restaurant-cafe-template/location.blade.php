{{-- restaurant-cafe-template/location.blade.php — {name, address, mapLabel, transport:[{icon,label,value,stroke}]} --}}

@php
$transportMeta = [
    'metro'    => ['emoji' => '🚇', 'label' => 'Metro',      'color' => '#1565c0'],
    'parking'  => ['emoji' => '🅿️', 'label' => 'Parking',    'color' => '#37474f'],
    'taxi'     => ['emoji' => '🚕', 'label' => 'Taxi / Cab', 'color' => '#f9a825'],
    'bus'      => ['emoji' => '🚌', 'label' => 'Bus',        'color' => '#e65100'],
    'walk'     => ['emoji' => '🚶', 'label' => 'Walk',       'color' => '#2e7d32'],
    'delivery' => ['emoji' => '🛵', 'label' => 'Delivery',   'color' => '#6a1b9a'],
    'auto'     => ['emoji' => '🛺', 'label' => 'Auto',       'color' => '#00695c'],
    'bike'     => ['emoji' => '🏍️', 'label' => 'Bike',       'color' => '#4e342e'],
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

                {{-- Row 1: Type + Label + Delete --}}
                <div class="row g-2 align-items-end mb-2">
                    <div class="col">
                        <label class="form-label small fw-semibold mb-1">Transport Type</label>
                        <select class="form-select form-select-sm" wire:model.live="form.transport.{{ $ti }}.icon">
                            @foreach($transportMeta as $tKey => $tInfo)
                            <option value="{{ $tKey }}">{{ $tInfo['emoji'] }} {{ $tInfo['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto d-flex align-items-end">
                        <button type="button"
                                class="btn btn-sm btn-outline-danger p-0 rounded-circle"
                                style="width:32px;height:32px;"
                                x-on:click="showConfirmToast('Remove this transport option?', () => $wire.removeRowWithConfirm({{ $ti }}, 'transport'))">
                            <i class="mdi mdi-delete-outline" style="font-size:14px;"></i>
                        </button>
                    </div>
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

