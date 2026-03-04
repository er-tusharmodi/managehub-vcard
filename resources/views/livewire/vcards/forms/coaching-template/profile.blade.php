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
        $logoPreview = $logoVal ? (isset($assetBaseUrl) ? rtrim($assetBaseUrl,'/').'/'.$logoVal : $logoVal) : null;
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

{{-- Logo Icon Class --}}
<div class="col-lg-4 mb-3">
    <label class="form-label fw-semibold" for="profile-iconClass">Logo Icon Class
        <small class="fw-normal text-muted">(Bootstrap icon fallback)</small>
    </label>
    <input type="text"
           id="profile-iconClass"
           class="form-control @error('form.logoIconClass') is-invalid @enderror"
           wire:model="form.logoIconClass"
           placeholder="bi-mortarboard-fill">
    @error('form.logoIconClass') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

{{-- Actions dict --}}
@if(isset($form['actions']) && is_array($form['actions']))
<div class="col-12 mt-1 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-cursor-default-click-outline me-1"></i>Action Buttons
        <small class="fw-normal text-muted ms-1 text-lowercase">(label shown or URL for link buttons)</small>
    </h6>
</div>
@foreach($form['actions'] as $actKey => $actVal)
    @php
        $actIsUrl = preg_match('/(whatsapp|email|share|url|link|maps|http)/i', $actKey)
                    || (is_string($actVal) && str_starts_with($actVal, 'http'));
    @endphp
    <div class="col-lg-4 mb-3">
        <label class="form-label fw-semibold" for="act-{{ $actKey }}">
            {{ \Illuminate\Support\Str::headline($actKey) }}
            @if($actIsUrl) <small class="text-muted fw-normal">(URL)</small> @endif
        </label>
        <input type="{{ $actIsUrl ? 'url' : 'text' }}"
               id="act-{{ $actKey }}"
               class="form-control form-control-sm @error('form.actions.' . $actKey) is-invalid @enderror"
               wire:model="form.actions.{{ $actKey }}"
               placeholder="{{ $actIsUrl ? 'https://' : 'Button label' }}">
        @error('form.actions.' . $actKey) <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
@endforeach
@endif
