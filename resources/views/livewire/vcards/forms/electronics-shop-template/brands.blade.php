{{--
 | electronics-shop-template/brands.blade.php
 | Brand name list: array of strings
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-shield-star-outline me-1"></i>Brands We Carry
    </h6>
    <small class="text-muted d-block mb-2">One brand name per field.</small>
</div>

@if(is_array($form))
@foreach($form as $i => $brand)
<div class="col-lg-3 col-sm-4 mb-2" wire:key="brand-{{ $i }}">
    <input type="text" class="form-control form-control-sm"
           wire:model="form.{{ $i }}" placeholder="Samsung">
</div>
@endforeach
@endif
