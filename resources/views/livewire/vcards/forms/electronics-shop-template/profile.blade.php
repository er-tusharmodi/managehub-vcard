{{--
 | electronics-shop-template/profile.blade.php
 | Staff profile: name, role, bio, badges[{text, className}]
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-account-outline me-1"></i>Owner / Staff Profile
    </h6>
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="ep-name">Name</label>
    <input type="text" id="ep-name"
           class="form-control @error('form.name') is-invalid @enderror"
           wire:model="form.name" placeholder="Ravi Electronics">
    @error('form.name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="ep-role">Role / Designation</label>
    <input type="text" id="ep-role"
           class="form-control @error('form.role') is-invalid @enderror"
           wire:model="form.role" placeholder="Certified Electronics Expert">
    @error('form.role') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="ep-bio">Bio / About</label>
    <textarea id="ep-bio" rows="2"
              class="form-control @error('form.bio') is-invalid @enderror"
              wire:model="form.bio"
              placeholder="15+ years of experience in electronics sales and repair..."></textarea>
    @error('form.bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

@php $badges = $form['badges'] ?? []; @endphp
@if(!empty($badges))
<div class="col-12 mb-1">
    <label class="form-label fw-semibold text-muted">Credential Badges</label>
</div>
@foreach($badges as $bi => $badge)
<div class="col-lg-6 mb-2" wire:key="ep-badge-{{ $bi }}">
    <div class="input-group input-group-sm">
        <input type="text" class="form-control"
               wire:model="form.badges.{{ $bi }}.text" placeholder="Authorized Service Center">
    </div>
</div>
@endforeach
@endif
