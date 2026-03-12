{{--
 | coaching-template/profile.blade.php
 | Profile section for coaching-template.
 | logoImageUrl → image upload, actions dict with mixed label/URL values.
--}}

<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-school-outline me-1"></i>Institute Profile
    </h6>
</div>

{{-- Logo Image --}}
<div class="col-12 mb-3">
    <label class="form-label fw-semibold">Institute Logo / Profile Image</label>
    @php
        $logoVal = $form['logoImageUrl'] ?? null;
        $logoPreview = $logoVal ? (str_starts_with($logoVal, '/') || str_starts_with($logoVal, 'http') ? $logoVal : (isset($assetBaseUrl) ? rtrim($assetBaseUrl,'/').'/'.$logoVal : $logoVal)) : null;
    @endphp
    <div class="d-flex align-items-start gap-3 flex-wrap">
        @if($logoPreview)
            <img src="{{ $logoPreview }}" class="rounded border" style="height:72px;width:72px;object-fit:contain;background:#f8f8f8;" alt="Logo preview">
        @endif
        <div class="flex-grow-1">
            <div wire:loading wire:target="uploads.logoImageUrl" class="mb-1">
                <span class="spinner-border spinner-border-sm text-primary"></span>
                <small class="text-primary ms-1">Uploading…</small>
            </div>
            <input type="file"
                   class="form-control @error('uploads.logoImageUrl') is-invalid @enderror"
                   wire:model.live="uploads.logoImageUrl"
                   accept="image/*">
            @error('uploads.logoImageUrl') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

{{-- Cover / Banner Image --}}
<div class="col-12 mb-3">
    <label class="form-label fw-semibold">Cover / Banner Image
        <small class="fw-normal text-muted">(replaces the animated banner background)</small>
    </label>
    @php
        $coverVal = $form['coverImageUrl'] ?? null;
        $coverPreview = $coverVal ? (str_starts_with($coverVal, '/') || str_starts_with($coverVal, 'http') ? $coverVal : (isset($assetBaseUrl) ? rtrim($assetBaseUrl,'/').'/'.$coverVal : $coverVal)) : null;
    @endphp
    <div class="d-flex align-items-start gap-3 flex-wrap">
        @if($coverPreview)
            <img src="{{ $coverPreview }}" class="rounded border" style="height:56px;width:120px;object-fit:cover;background:#f8f8f8;" alt="Cover preview">
        @endif
        <div class="flex-grow-1">
            <div wire:loading wire:target="uploads.coverImageUrl" class="mb-1">
                <span class="spinner-border spinner-border-sm text-primary"></span>
                <small class="text-primary ms-1">Uploading…</small>
            </div>
            <input type="file"
                   class="form-control @error('uploads.coverImageUrl') is-invalid @enderror"
                   wire:model.live="uploads.coverImageUrl"
                   accept="image/*">
            @error('uploads.coverImageUrl') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

{{-- Est Tag --}}
<div class="col-lg-4 mb-3">
    <label class="form-label fw-semibold" for="profile-estTag">Established Tag</label>
    <input type="text"
           id="profile-estTag"
           class="form-control @error('form.estTag') is-invalid @enderror"
           wire:model="form.estTag"
           placeholder="Est. 2008">
    @error('form.estTag') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-4 mb-3">
    <label class="form-label fw-semibold" for="profile-name">Institute Name</label>
    <input type="text"
           id="profile-name"
           class="form-control @error('form.name') is-invalid @enderror"
           wire:model="form.name"
           placeholder="Pinnacle Excellence Academy">
    @error('form.name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Role (exam categories) --}}
<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="profile-role">Courses / Category Tagline</label>
    <input type="text"
           id="profile-role"
           class="form-control @error('form.role') is-invalid @enderror"
           wire:model="form.role"
           placeholder="UPSC · SSC · Banking · State PSC">
    @error('form.role') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Qualifications line --}}
<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="profile-qual">Founder / Qualifications Line</label>
    <input type="text"
           id="profile-qual"
           class="form-control @error('form.qual') is-invalid @enderror"
           wire:model="form.qual"
           placeholder="Founded by Dr. Name (IRS Retd.) · IIT Delhi · 20+ Yrs">
    @error('form.qual') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Action Button Labels are hardcoded in the blade template — not editable --}}
