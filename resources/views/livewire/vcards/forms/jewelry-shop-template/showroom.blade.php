{{-- jewelry-shop-template/showroom.blade.php — Showroom address {name, line1, line2} --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-store-marker me-1"></i>Showroom Details
    </h6>
</div>
<div class="col-md-6">
    <label class="form-label small mb-1 fw-semibold">Showroom Name</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.name" placeholder="Lalitha Gold Palace">
</div>
<div class="col-md-6">
    <label class="form-label small mb-1 fw-semibold">Address Line 1</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.line1" placeholder="12, Jewellers Street, T. Nagar">
</div>
<div class="col-md-6">
    <label class="form-label small mb-1 fw-semibold">Address Line 2</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.line2" placeholder="Chennai — 600 017">
</div>
