{{--
 | electronics-shop-template/location.blade.php
 | Location: titleLine, addressLine (mapLabel hidden)
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-map-marker-outline me-1"></i>Store Location
    </h6>
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="el-title">Section Title Line</label>
    <input type="text" id="el-title"
           class="form-control @error('form.titleLine') is-invalid @enderror"
           wire:model="form.titleLine" placeholder="Visit Our Store">
    @error('form.titleLine') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="el-addr">Address</label>
    <input type="text" id="el-addr"
           class="form-control @error('form.addressLine') is-invalid @enderror"
           wire:model="form.addressLine" placeholder="Shop 12, Tech Hub Mall, MG Road, Bengaluru">
    @error('form.addressLine') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
