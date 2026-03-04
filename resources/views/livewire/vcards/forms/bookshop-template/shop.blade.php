{{--
 | bookshop-template/shop.blade.php
 | Shop identity & media fields for bookshop-template.
 | Note: phone/whatsapp/email/maps/website/address are synced via _common.
 | Available: $form (array), $uploads (array)
--}}

{{-- ── Store Identity ────────────────────────────────────────────── --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-store-outline me-1"></i>Store Identity
    </h6>
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="shop-name">Store Name</label>
    <input type="text"
           id="shop-name"
           class="form-control @error('form.name') is-invalid @enderror"
           wire:model="form.name"
           placeholder="PageTurner Books">
    @error('form.name') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="shop-tagline">Tagline</label>
    <input type="text"
           id="shop-tagline"
           class="form-control @error('form.tagline') is-invalid @enderror"
           wire:model="form.tagline"
           placeholder="Your Neighbourhood Bookshop & Reading Hub">
    @error('form.tagline') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- ── Contact ───────────────────────────────────────────────────── --}}
<div class="col-12 mb-2 mt-1">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-phone-outline me-1"></i>Contact
        <span class="text-info ms-1" style="font-weight:400;text-transform:none;font-size:.75rem;">
            (also editable in <strong>Basic Info</strong>)
        </span>
    </h6>
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="shop-phone">Phone Number</label>
    <input type="tel"
           id="shop-phone"
           class="form-control @error('form.phone') is-invalid @enderror"
           wire:model="form.phone"
           placeholder="+919876543210">
    @error('form.phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="shop-whatsapp">WhatsApp</label>
    <input type="tel"
           id="shop-whatsapp"
           class="form-control @error('form.whatsapp') is-invalid @enderror"
           wire:model="form.whatsapp"
           placeholder="919876543210">
    @error('form.whatsapp') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <small class="text-muted">Digits only with country code</small>
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="shop-email">Email</label>
    <input type="email"
           id="shop-email"
           class="form-control @error('form.email') is-invalid @enderror"
           wire:model="form.email"
           placeholder="hello@yourbookshop.com">
    @error('form.email') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="shop-website">Website</label>
    <input type="url"
           id="shop-website"
           class="form-control @error('form.website') is-invalid @enderror"
           wire:model="form.website"
           placeholder="https://yourbookshop.com">
    @error('form.website') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="shop-maps">Google Maps URL</label>
    <input type="url"
           id="shop-maps"
           class="form-control @error('form.maps') is-invalid @enderror"
           wire:model="form.maps"
           placeholder="https://maps.google.com/?q=...">
    @error('form.maps') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="shop-address">Address</label>
    <textarea id="shop-address"
              class="form-control @error('form.address') is-invalid @enderror"
              wire:model="form.address"
              rows="2"
              placeholder="Street, City, State, PIN"></textarea>
    @error('form.address') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- ── Cover & Avatar Images ──────────────────────────────────────── --}}
<div class="col-12 mb-2 mt-1">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-image-outline me-1"></i>Images
    </h6>
</div>

{{-- Cover Image --}}
<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold">Cover / Banner Image</label>
    @if(!empty($form['coverImage'] ?? ''))
        <div class="mb-2">
            <img src="{{ $form['coverImage'] }}" alt="Cover"
                 class="img-thumbnail"
                 style="max-width:220px;max-height:100px;object-fit:cover;">
        </div>
    @endif
    <div wire:loading wire:target="uploads.coverImage" class="mb-1">
        <span class="spinner-border spinner-border-sm text-primary"></span>
        <small class="text-primary ms-1">Uploading…</small>
    </div>
    <input type="file"
           class="form-control @error('uploads.coverImage') is-invalid @enderror"
           wire:model.live="uploads.coverImage"
           accept="image/*">
    @error('uploads.coverImage') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <small class="text-muted">Recommended: 1200×400 px, JPG/PNG</small>
</div>

{{-- Cover Alt --}}
<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="shop-cover-alt">Cover Image Alt Text</label>
    <input type="text"
           id="shop-cover-alt"
           class="form-control @error('form.coverAlt') is-invalid @enderror"
           wire:model="form.coverAlt"
           placeholder="PageTurner Books Cover">
    @error('form.coverAlt') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <small class="text-muted">Describes the image for accessibility & SEO</small>
</div>

{{-- Avatar Image --}}
<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold">Store / Profile Picture</label>
    @if(!empty($form['avatarImage'] ?? ''))
        <div class="mb-2">
            <img src="{{ $form['avatarImage'] }}" alt="Avatar"
                 class="img-thumbnail rounded-circle"
                 style="width:80px;height:80px;object-fit:cover;">
        </div>
    @endif
    <div wire:loading wire:target="uploads.avatarImage" class="mb-1">
        <span class="spinner-border spinner-border-sm text-primary"></span>
        <small class="text-primary ms-1">Uploading…</small>
    </div>
    <input type="file"
           class="form-control @error('uploads.avatarImage') is-invalid @enderror"
           wire:model.live="uploads.avatarImage"
           accept="image/*">
    @error('uploads.avatarImage') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <small class="text-muted">Square logo/photo, recommended 500×500 px</small>
</div>

{{-- ── vCard / QR File Names (auto-generated) ─────────────────────── --}}
<div class="col-12 mb-2 mt-1">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-file-outline me-1"></i>Generated Files
    </h6>
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="shop-vcardFileName">vCard File Name</label>
    <input type="text"
           id="shop-vcardFileName"
           class="form-control @error('form.vcardFileName') is-invalid @enderror"
           wire:model="form.vcardFileName"
           placeholder="YourBusiness.vcf">
    @error('form.vcardFileName') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <small class="text-muted">Filename when user downloads contact card (.vcf)</small>
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="shop-qrFileName">QR Code File Name</label>
    <input type="text"
           id="shop-qrFileName"
           class="form-control @error('form.qrFileName') is-invalid @enderror"
           wire:model="form.qrFileName"
           placeholder="yourbusiness_qr.png">
    @error('form.qrFileName') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <small class="text-muted">Filename when user downloads QR code (.png)</small>
</div>
