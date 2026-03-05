{{-- restaurant-cafe-template/location.blade.php — {name, address, transport[]: {icon, label, value, stroke}} --}}
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

@if(is_array($form['transport'] ?? null) && count($form['transport']) > 0)
<div class="col-12 mt-2">
    <label class="form-label small mb-1 fw-semibold">How to Get Here (Transport Options)</label>
    @foreach(($form['transport'] ?? []) as $ti => $tr)
    <div class="border rounded-2 p-2 mb-1 bg-light" wire:key="rtrans-{{ $ti }}">
        <div class="row g-2 align-items-center">
            <div class="col-sm-2">
                <label class="form-label small mb-0">Icon</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.transport.{{ $ti }}.icon" placeholder="🚇">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-0">Label</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.transport.{{ $ti }}.label" placeholder="Metro">
            </div>
            <div class="col-sm-5">
                <label class="form-label small mb-0">Value</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.transport.{{ $ti }}.value" placeholder="Indiranagar Metro – Exit 2">
            </div>
            <div class="col-sm-2">
                <label class="form-label small mb-0">Stroke</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.transport.{{ $ti }}.stroke" placeholder="#e74c3c">
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
