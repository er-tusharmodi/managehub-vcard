{{--
 | _shared/_common.blade.php
 | Shared "Basic Info" form — used by ALL templates.
 | Available blade scope: $form (array), $categoryOptions (array), $section (string)
--}}

{{-- ── Contact Details ───────────────────────────────────────────── --}}
<div class="col-12 mb-3">
    <h6 class="fw-semibold text-muted text-uppercase mb-3" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-store-outline me-1"></i>Business Identity
    </h6>
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="common-name">Business Name</label>
    <input type="text"
           id="common-name"
           class="form-control @error('form.name') is-invalid @enderror"
           wire:model="form.name"
           placeholder="Your business name">
    @error('form.name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="common-tagline">Tagline / Subtitle</label>
    <input type="text"
           id="common-tagline"
           class="form-control @error('form.tagline') is-invalid @enderror"
           wire:model="form.tagline"
           placeholder="A short description of your business">
    @error('form.tagline')
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
           placeholder="hello@yourbusiness.com">
    @error('form.email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="common-website">Website URL</label>
    <input type="url"
           id="common-website"
           class="form-control @error('form.website') is-invalid @enderror"
           wire:model="form.website"
           placeholder="https://yourbusiness.com">
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
    <label class="form-label fw-semibold" for="common-address">Full Address</label>
    <textarea id="common-address"
              class="form-control @error('form.address') is-invalid @enderror"
              wire:model="form.address"
              rows="2"
              placeholder="Street, City, State, PIN"></textarea>
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


