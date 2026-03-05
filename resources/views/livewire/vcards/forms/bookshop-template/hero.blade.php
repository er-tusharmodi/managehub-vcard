{{--
 | bookshop-template/hero.blade.php
 | Hero section labels/buttons for bookshop-template.
 | Available: $form (array)
--}}

{{-- Header Buttons and Action Button Labels are hardcoded in the blade template — not editable --}}

{{-- ── Profile Card ─────────────────────────────────────────────── --}}
<div class="col-12 mb-2 mt-1">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-card-account-details-outline me-1"></i>Profile Card
    </h6>
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="hero-profile-name">Name / Title</label>
    <input type="text"
           id="hero-profile-name"
           class="form-control @error('form.profile.name') is-invalid @enderror"
           wire:model="form.profile.name"
           placeholder="PageTurner Books">
    @error('form.profile.name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="hero-profile-role">Role / Subtitle</label>
    <input type="text"
           id="hero-profile-role"
           class="form-control @error('form.profile.role') is-invalid @enderror"
           wire:model="form.profile.role"
           placeholder="📖 Your Neighbourhood Bookshop">
    @error('form.profile.role') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="hero-profile-bio">Bio / Description</label>
    <textarea id="hero-profile-bio"
              class="form-control @error('form.profile.bio') is-invalid @enderror"
              wire:model="form.profile.bio"
              rows="3"
              placeholder="Tell customers about your store…"></textarea>
    @error('form.profile.bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Action Button Labels are hardcoded in the blade template — not editable --}}
