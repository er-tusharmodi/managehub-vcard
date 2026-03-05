{{-- sweetshop-template/location.blade.php — {line1, line2} (mapButtonLabel is static) --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-map-marker me-1"></i>Location
    </h6>
</div>
<div class="col-md-6">
    <label class="form-label small mb-1 fw-semibold">Address Line 1</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.line1" placeholder="Shop No. 5, Sadar Bazaar">
</div>
<div class="col-md-6">
    <label class="form-label small mb-1 fw-semibold">Address Line 2</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.line2" placeholder="Agra, Uttar Pradesh — 282003">
</div>
