{{--
 | electronics-shop-template/enquiryForm.blade.php
 | Enquiry form settings: defaultEmail, defaultCategory, defaultMessage, categories[]
 | Form field labels & placeholders are static — not shown.
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-email-outline me-1"></i>Enquiry Form Settings
    </h6>
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="eq-email">Default Recipient Email</label>
    <input type="email" id="eq-email"
           class="form-control @error('form.defaultEmail') is-invalid @enderror"
           wire:model="form.defaultEmail" placeholder="shop@example.com">
    @error('form.defaultEmail') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="eq-cat">Default Category</label>
    <input type="text" id="eq-cat"
           class="form-control @error('form.defaultCategory') is-invalid @enderror"
           wire:model="form.defaultCategory" placeholder="mobiles">
    @error('form.defaultCategory') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="eq-msg">Default Enquiry Message</label>
    <textarea id="eq-msg" rows="2"
              class="form-control @error('form.defaultMessage') is-invalid @enderror"
              wire:model="form.defaultMessage"
              placeholder="Hi, I would like to enquire about..."></textarea>
    @error('form.defaultMessage') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Category dropdown options --}}
@php $cats = $form['categories'] ?? []; @endphp
@if(!empty($cats))
<div class="col-12 mb-1">
    <label class="form-label fw-semibold text-muted">Category Dropdown Options</label>
</div>
@foreach($cats as $ci => $cat)
<div class="col-lg-4 mb-2" wire:key="eq-cat-{{ $ci }}">
    <input type="text" class="form-control form-control-sm"
           wire:model="form.categories.{{ $ci }}" placeholder="Mobile Phones">
</div>
@endforeach
@endif
