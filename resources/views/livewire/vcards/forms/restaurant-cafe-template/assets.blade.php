{{--
 | restaurant-cafe-template/assets.blade.php
 | Overrides _shared/assets.blade.php — shows only bannerImage + qrCodeImage.
 | profileImage is intentionally excluded for the restaurant template.
--}}
@php
    $allowedKeys = ['bannerImage', 'qrCodeImage'];
    $assetMeta   = [
        'bannerImage' => [
            'label' => 'Banner / Cover Image',
            'help'  => 'Recommended: 1200×400 px, JPG/PNG — full-width background shown behind the banner text.',
            'icon'  => 'mdi-image-outline',
            'color' => '#0d6efd',
        ],
        'qrCodeImage' => [
            'label' => 'QR Code Image',
            'help'  => 'Auto-generated — upload only if you have a custom QR code.',
            'icon'  => 'mdi-qrcode',
            'color' => '#6c757d',
        ],
    ];
@endphp

<div class="col-12 mb-3">
    <h6 class="fw-semibold text-muted text-uppercase mb-1" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-image-multiple-outline me-1"></i>Image Assets
    </h6>
    <small class="text-muted">Upload or replace images used across the vCard.</small>
</div>

@foreach($allowedKeys as $imgKey)
@if(!array_key_exists($imgKey, $form)) @continue @endif
@php
    $meta        = $assetMeta[$imgKey];
    $imgValue    = $form[$imgKey] ?? '';
    $imgPreview  = $imgValue;
    if (isset($assetBaseUrl) && is_string($imgPreview) && !empty($imgPreview)
        && !preg_match('~^(https?:)?//|data:|/~', $imgPreview)) {
        $imgPreview = rtrim($assetBaseUrl, '/') . '/' . ltrim($imgPreview, '/');
    }
@endphp

<div class="col-lg-6 mb-4">
    <div class="border rounded-3 overflow-hidden shadow-sm">
        <div class="d-flex align-items-center gap-2 px-3 py-2"
             style="background:linear-gradient(90deg,#f8fafc,#f1f5f9);border-bottom:1px solid #e2e8f0;">
            <i class="mdi {{ $meta['icon'] }}" style="color:{{ $meta['color'] }};font-size:1rem;"></i>
            <span class="fw-semibold" style="font-size:.85rem;">{{ $meta['label'] }}</span>
        </div>
        <div class="p-3">
            @if(!empty($imgValue))
                <div class="mb-2">
                    <img src="{{ $imgPreview }}" alt="{{ $meta['label'] }}"
                         class="rounded-2 border"
                         style="max-width:100%;max-height:120px;object-fit:cover;">
                </div>
            @endif

            <div wire:loading wire:target="uploads.{{ $imgKey }}" class="mb-2">
                <span class="spinner-border spinner-border-sm text-primary"></span>
                <small class="text-primary ms-1">Uploading…</small>
            </div>

            <input type="file"
                   class="form-control form-control-sm @error('uploads.' . $imgKey) is-invalid @enderror"
                   wire:model.live="uploads.{{ $imgKey }}"
                   accept="image/*">
            @error('uploads.' . $imgKey)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted d-block mt-1">{{ $meta['help'] }}</small>
        </div>
    </div>
</div>
@endforeach
