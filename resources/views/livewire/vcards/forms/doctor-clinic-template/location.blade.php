{{--
 | doctor-clinic-template/location.blade.php
 | Location: clinicName, line1, line2 (mapLabel is static — not shown)
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-map-marker-outline me-1"></i>Clinic Location
    </h6>
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="loc-clinic">Clinic Name (displayed on map card)</label>
    <input type="text" id="loc-clinic"
           class="form-control @error('form.clinicName') is-invalid @enderror"
           wire:model="form.clinicName" placeholder="Sharma Heart Care Centre">
    @error('form.clinicName') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="loc-l1">Address Line 1</label>
    <input type="text" id="loc-l1"
           class="form-control @error('form.line1') is-invalid @enderror"
           wire:model="form.line1" placeholder="201, Health Plaza, Linking Road">
    @error('form.line1') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="loc-l2">Address Line 2</label>
    <input type="text" id="loc-l2"
           class="form-control @error('form.line2') is-invalid @enderror"
           wire:model="form.line2" placeholder="Bandra West, Mumbai – 400050">
    @error('form.line2') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
