{{--
 | _shared/shop.blade.php
 | Generic shop/business info section — used by templates that don't have a
 | template-specific shop partial.
 |
 | Renders the standard contact fields with correct types, and falls back to
 | the generic field.blade.php for any extra template-specific keys.
 |
 | Available: $form (array), $categoryOptions (array)
--}}

@php
    // Standard shop keys rendered statically (known types)
    $staticKeys = ['name', 'tagline', 'subtitle', 'phone', 'whatsapp', 'email',
                   'address', 'maps', 'website'];
@endphp

{{-- ── Business Identity ──────────────────────────────────────────── --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-store-outline me-1"></i>Business Identity
    </h6>
</div>

@if(array_key_exists('name', $form))
    <div class="col-lg-6 mb-3">
        <label class="form-label fw-semibold" for="shop-name">Business Name</label>
        <input type="text"
               id="shop-name"
               class="form-control @error('form.name') is-invalid @enderror"
               wire:model="form.name"
               placeholder="Your business name">
        @error('form.name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
@endif

@if(array_key_exists('tagline', $form))
    <div class="col-lg-6 mb-3">
        <label class="form-label fw-semibold" for="shop-tagline">Tagline</label>
        <input type="text"
               id="shop-tagline"
               class="form-control @error('form.tagline') is-invalid @enderror"
               wire:model="form.tagline"
               placeholder="A catchy tagline for your business">
        @error('form.tagline') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
@endif

@if(array_key_exists('subtitle', $form))
    <div class="col-lg-6 mb-3">
        <label class="form-label fw-semibold" for="shop-subtitle">Subtitle</label>
        <input type="text"
               id="shop-subtitle"
               class="form-control @error('form.subtitle') is-invalid @enderror"
               wire:model="form.subtitle"
               placeholder="Subtitle or descriptor">
        @error('form.subtitle') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
@endif

{{-- ── Contact ─────────────────────────────────────────────────────── --}}
<div class="col-12 mb-2 mt-1">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-phone-outline me-1"></i>Contact
        <span class="text-info ms-1" style="font-weight:400;text-transform:none;font-size:.75rem;">
            (also editable in <strong>Basic Info</strong>)
        </span>
    </h6>
</div>

@if(array_key_exists('phone', $form))
    <div class="col-lg-6 mb-3">
        <label class="form-label fw-semibold" for="shop-phone">Phone Number</label>
        <input type="tel"
               id="shop-phone"
               class="form-control @error('form.phone') is-invalid @enderror"
               wire:model="form.phone"
               placeholder="+919876543210">
        @error('form.phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
@endif

@if(array_key_exists('whatsapp', $form))
    <div class="col-lg-6 mb-3">
        <label class="form-label fw-semibold" for="shop-whatsapp">WhatsApp Number</label>
        <input type="tel"
               id="shop-whatsapp"
               class="form-control @error('form.whatsapp') is-invalid @enderror"
               wire:model="form.whatsapp"
               placeholder="919876543210">
        @error('form.whatsapp') <div class="invalid-feedback">{{ $message }}</div> @enderror
        <small class="text-muted">Digits only with country code</small>
    </div>
@endif

@if(array_key_exists('email', $form))
    <div class="col-lg-6 mb-3">
        <label class="form-label fw-semibold" for="shop-email">Email Address</label>
        <input type="email"
               id="shop-email"
               class="form-control @error('form.email') is-invalid @enderror"
               wire:model="form.email"
               placeholder="hello@yourbusiness.com">
        @error('form.email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
@endif

@if(array_key_exists('website', $form))
    <div class="col-lg-6 mb-3">
        <label class="form-label fw-semibold" for="shop-website">Website URL</label>
        <input type="url"
               id="shop-website"
               class="form-control @error('form.website') is-invalid @enderror"
               wire:model="form.website"
               placeholder="https://yourbusiness.com">
        @error('form.website') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
@endif

@if(array_key_exists('maps', $form))
    <div class="col-lg-6 mb-3">
        <label class="form-label fw-semibold" for="shop-maps">Google Maps URL</label>
        <input type="url"
               id="shop-maps"
               class="form-control @error('form.maps') is-invalid @enderror"
               wire:model="form.maps"
               placeholder="https://maps.google.com/?q=...">
        @error('form.maps') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
@endif

@if(array_key_exists('address', $form))
    <div class="col-12 mb-3">
        <label class="form-label fw-semibold" for="shop-address">Full Address</label>
        <textarea id="shop-address"
                  class="form-control @error('form.address') is-invalid @enderror"
                  wire:model="form.address"
                  rows="2"
                  placeholder="Street, City, State, PIN"></textarea>
        @error('form.address') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
@endif

{{-- ── Remaining template-specific fields ────────────────────────── --}}
@php
    $extraKeys = array_diff(array_keys($form), $staticKeys);
@endphp

@if(!empty($extraKeys))
    <div class="col-12 mb-2 mt-1">
        <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
            <i class="mdi mdi-dots-horizontal me-1"></i>Additional Fields
        </h6>
    </div>
    @foreach($extraKeys as $extraKey)
        @include('livewire.vcards.partials.field', [
            'key'             => $extraKey,
            'value'           => $form[$extraKey],
            'wirePath'        => $extraKey,
            'categoryOptions' => $categoryOptions ?? [],
        ])
    @endforeach
@endif
