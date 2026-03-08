{{-- restaurant-cafe-template/location.blade.php — {name, address, mapLabel?, transport:[{icon,label,value,stroke}]} --}}

<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-map-marker me-1"></i>Location
    </h6>
</div>

<div class="col-md-6">
    <label class="form-label small mb-1 fw-semibold">Restaurant Name</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.name" placeholder="La Cucina Italiana">
</div>
<div class="col-12">
    <label class="form-label small mb-1 fw-semibold">Full Address</label>
    <textarea class="form-control form-control-sm" rows="2"
              wire:model="form.address" placeholder="12, MG Road, Indiranagar, Bangalore — 560038"></textarea>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- TRANSPORT OPTIONS                                                           --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div class="col-12 mt-3">
    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom mb-2">
        <span class="fw-semibold text-uppercase text-muted" style="font-size:.7rem;letter-spacing:.07em;">
            <i class="mdi mdi-bus me-1"></i>How to Get Here (Transport Options)
            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ count($form['transport'] ?? []) }}</span>
        </span>
        <button type="button" class="btn btn-sm btn-primary px-3"
                wire:click="addRowAndSave('transport', ['icon','label','value','stroke'])">
            <i class="mdi mdi-plus me-1"></i>Add Transport
        </button>
    </div>

    @if(!empty($form['transport'] ?? []))
    @foreach(($form['transport'] ?? []) as $ti => $tr)
    <div class="border rounded-2 p-2 mb-2 bg-light" wire:key="rc-trans-{{ $ti }}">
        <div class="row g-2 align-items-center">
            <div class="col-sm-1">
                <label class="form-label small mb-0 fw-semibold">Icon</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.transport.{{ $ti }}.icon" placeholder="🚇">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-0 fw-semibold">Label</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.transport.{{ $ti }}.label" placeholder="Metro">
            </div>
            <div class="col-sm-5">
                <label class="form-label small mb-0 fw-semibold">Description / Value</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.transport.{{ $ti }}.value" placeholder="Indiranagar Metro – Exit 2">
            </div>
            <div class="col-sm-2">
                <label class="form-label small mb-0 fw-semibold">Stroke Color</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.transport.{{ $ti }}.stroke" placeholder="#e74c3c">
            </div>
            <div class="col-sm-1 d-flex align-items-end">
                <button type="button"
                        class="btn btn-sm btn-outline-danger p-0 rounded-circle"
                        style="width:28px;height:28px;"
                        x-on:click="showConfirmToast('Remove this transport option?', () => $wire.removeRowWithConfirm({{ $ti }}, 'transport'))">
                    <i class="mdi mdi-delete" style="font-size:12px;"></i>
                </button>
            </div>
        </div>
    </div>
    @endforeach
    @else
    <div class="text-center py-4 rounded-3" style="border:2px dashed #cbd5e1;background:#f8fafc;">
        <i class="mdi mdi-bus fs-2 text-muted mb-1 d-block"></i>
        <p class="text-muted small mb-0">No transport options yet. Add directions like Metro, Bus, Parking etc.</p>
    </div>
    @endif
</div>
