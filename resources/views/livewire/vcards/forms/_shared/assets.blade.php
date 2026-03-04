{{--
 | _shared/assets.blade.php
 | Image assets section — used by templates with an `assets` dict.
 | Renders each key as an image upload with preview.
 | Unknown keys fall back to the generic field renderer.
 |
 | Available: $form (array), $uploads (array)
--}}

@php
    $assetLabels = [
        'bannerImage'  => ['Banner / Cover Image', 'Recommended: 1200×400 px, JPG/PNG'],
        'profileImage' => ['Profile / Logo Image', 'Recommended: 500×500 px, PNG/JPG'],
        'serviceImage' => ['Service Section Image', 'Image shown in services block'],
        'qrCodeImage'  => ['QR Code Image', 'Auto-generated — upload only if you have a custom QR'],
        'coverImage'   => ['Cover Image', 'Recommended: 1200×400 px'],
        'avatarImage'  => ['Avatar / Logo', 'Recommended: 500×500 px'],
        'logoImage'    => ['Logo Image', 'Recommended: 400×400 px, transparent PNG'],
        'bgImage'      => ['Background Image', 'Full-width background image'],
    ];
    $knownImageKeys = array_keys($assetLabels);
    $genericKeys    = array_diff(array_keys($form), $knownImageKeys);
@endphp

<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-image-multiple-outline me-1"></i>Image Assets
    </h6>
    <p class="text-muted mb-3" style="font-size:.82rem;">Upload or replace images used across the vCard.</p>
</div>

@foreach($form as $imgKey => $imgValue)
    @if(!in_array($imgKey, $knownImageKeys))
        @continue
    @endif
    @php [$imgLabel, $imgHelp] = $assetLabels[$imgKey]; @endphp

    <div class="col-lg-6 mb-4">
        <label class="form-label fw-semibold">{{ $imgLabel }}</label>
        @if(!empty($imgValue))
            <div class="mb-2">
                <img src="{{ $imgValue }}" alt="{{ $imgLabel }}"
                     class="img-thumbnail"
                     style="max-width:220px;max-height:110px;object-fit:cover;">
            </div>
        @endif
        <div wire:loading wire:target="uploads.{{ $imgKey }}" class="mb-1">
            <span class="spinner-border spinner-border-sm text-primary"></span>
            <small class="text-primary ms-1">Uploading…</small>
        </div>
        <input type="file"
               class="form-control @error('uploads.' . $imgKey) is-invalid @enderror"
               wire:model.live="uploads.{{ $imgKey }}"
               accept="image/*">
        @error('uploads.' . $imgKey) <div class="invalid-feedback">{{ $message }}</div> @enderror
        <small class="text-muted">{{ $imgHelp }}</small>
    </div>
@endforeach

{{-- Text fields within assets (like profileAlt) --}}
@foreach($form as $aKey => $aValue)
    @if(!is_string($aValue) || in_array($aKey, $knownImageKeys)) @continue @endif
    <div class="col-lg-6 mb-3">
        <label class="form-label fw-semibold" for="asset-{{ $aKey }}">
            {{ \Illuminate\Support\Str::headline($aKey) }}
        </label>
        <input type="text"
               id="asset-{{ $aKey }}"
               class="form-control @error('form.' . $aKey) is-invalid @enderror"
               wire:model="form.{{ $aKey }}">
        @error('form.' . $aKey) <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
@endforeach
