{{-- mens-salon-template/location.blade.php — {address} (title and mapLabel are static) --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-map-marker me-1"></i>Location
    </h6>
</div>
<div class="col-12">
    <label class="form-label small mb-1 fw-semibold">Address</label>
    <textarea class="form-control form-control-sm" rows="2"
              wire:model="form.address" placeholder="Shop No 4, Green Avenue, Sector 21, Gurugram"></textarea>
</div>
