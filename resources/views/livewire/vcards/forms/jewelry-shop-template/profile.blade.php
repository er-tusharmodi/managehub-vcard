{{--
 | jewelry-shop-template/profile.blade.php
 | Owner profile: name, role, stars, rating, ratingCount, bio
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-account-circle-outline me-1"></i>Owner Profile
    </h6>
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="jp-name">Name</label>
    <input type="text" id="jp-name"
           class="form-control @error('form.name') is-invalid @enderror"
           wire:model="form.name" placeholder="Arjun Mehta">
    @error('form.name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="jp-role">Role / Title</label>
    <input type="text" id="jp-role"
           class="form-control @error('form.role') is-invalid @enderror"
           wire:model="form.role" placeholder="Master Jeweller & GIA Certified Gemologist">
    @error('form.role') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-3 mb-3">
    <label class="form-label fw-semibold" for="jp-rating">Rating</label>
    <input type="text" id="jp-rating"
           class="form-control @error('form.rating') is-invalid @enderror"
           wire:model="form.rating" placeholder="4.9">
    @error('form.rating') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-3 mb-3">
    <label class="form-label fw-semibold" for="jp-rc">Rating Count</label>
    <input type="text" id="jp-rc"
           class="form-control @error('form.ratingCount') is-invalid @enderror"
           wire:model="form.ratingCount" placeholder="218 reviews">
    @error('form.ratingCount') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="jp-bio">Bio</label>
    <textarea id="jp-bio" rows="2"
              class="form-control @error('form.bio') is-invalid @enderror"
              wire:model="form.bio"
              placeholder="Third-generation goldsmith with 25+ years of craftsmanship..."></textarea>
    @error('form.bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
