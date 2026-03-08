{{--
 | doctor-clinic-template/_common.blade.php
 | "Basic Info" for doctor – merges Doctor section + contact details.
 | Covers: name, role, qualification, regNumber, clinicName,
 |         phone, whatsapp, email, website, address, maps.
 | Social is managed via the dedicated "Social Links" section.
--}}

{{-- ── Doctor Identity ──────────────────────────────────────────────── --}}
<div class="col-12 mb-3">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-doctor me-1"></i>Doctor Details
    </h6>
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="common-name">Doctor Name</label>
    <input type="text"
           id="common-name"
           class="form-control @error('form.name') is-invalid @enderror"
           wire:model="form.name"
           placeholder="Dr. Priya Sharma">
    @error('form.name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="common-role">Specialization</label>
    <input type="text"
           id="common-role"
           class="form-control @error('form.role') is-invalid @enderror"
           wire:model="form.role"
           placeholder="Family & General Physician">
    @error('form.role')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-lg-9 mb-3">
    <label class="form-label fw-semibold" for="common-qualification">Qualifications</label>
    <input type="text"
           id="common-qualification"
           class="form-control @error('form.qualification') is-invalid @enderror"
           wire:model="form.qualification"
           placeholder="MBBS, MD (Internal Medicine), 18 Years Experience">
    @error('form.qualification')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-lg-3 mb-3">
    <label class="form-label fw-semibold" for="common-regNumber">Reg. Number</label>
    <input type="text"
           id="common-regNumber"
           class="form-control @error('form.regNumber') is-invalid @enderror"
           wire:model="form.regNumber"
           placeholder="MH-12345">
    @error('form.regNumber')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="common-clinicName">Clinic / Hospital Name</label>
    <input type="text"
           id="common-clinicName"
           class="form-control @error('form.clinicName') is-invalid @enderror"
           wire:model="form.clinicName"
           placeholder="Sharma Family Clinic">
    @error('form.clinicName')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- ── Contact ─────────────────────────────────────────────────────── --}}
<div class="col-12 mb-2 mt-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-phone-outline me-1"></i>Contact
    </h6>
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="common-phone">Phone Number</label>
    <input type="tel"
           id="common-phone"
           class="form-control @error('form.phone') is-invalid @enderror"
           wire:model="form.phone"
           placeholder="+91 98765 43210">
    @error('form.phone')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="text-muted">Include country code, e.g. +91XXXXXXXXXX</small>
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="common-whatsapp">WhatsApp Number</label>
    <input type="tel"
           id="common-whatsapp"
           class="form-control @error('form.whatsapp') is-invalid @enderror"
           wire:model="form.whatsapp"
           placeholder="919876543210">
    @error('form.whatsapp')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="text-muted">Digits only with country code, e.g. 919876543210</small>
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="common-email">Email Address</label>
    <input type="email"
           id="common-email"
           class="form-control @error('form.email') is-invalid @enderror"
           wire:model="form.email"
           placeholder="doctor@example.com">
    @error('form.email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="common-website">Website / Practo URL</label>
    <input type="url"
           id="common-website"
           class="form-control @error('form.website') is-invalid @enderror"
           wire:model="form.website"
           placeholder="https://practo.com/your-profile">
    @error('form.website')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

{{-- ── Location ─────────────────────────────────────────────────────── --}}
<div class="col-12 mb-2 mt-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-map-marker-outline me-1"></i>Location
    </h6>
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="common-address">Clinic Address</label>
    <textarea id="common-address"
              class="form-control @error('form.address') is-invalid @enderror"
              wire:model="form.address"
              rows="3"
              placeholder="Shop No. 4, Raj Complex, Andheri West, Mumbai – 400058"></textarea>
    @error('form.address')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="common-maps">Google Maps URL</label>
    <input type="url"
           id="common-maps"
           class="form-control @error('form.maps') is-invalid @enderror"
           wire:model="form.maps"
           placeholder="https://maps.google.com/?q=...">
    @error('form.maps')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <small class="text-muted">
        Paste from <a href="https://maps.google.com" target="_blank" class="text-primary">Google Maps</a>
        → Share → Copy link
    </small>
</div>
