{{--
 | electronics-shop-template/categories.blade.php
 | Product categories: [{key, name, count, query}]
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-tag-multiple-outline me-1"></i>Product Categories
    </h6>
</div>

@if(is_array($form))
@foreach($form as $i => $cat)
<div class="col-12 mb-2" wire:key="cat-{{ $i }}">
    <div class="border rounded-3 p-2 bg-light">
        <div class="row g-2">
            <div class="col-sm-2">
                <label class="form-label small mb-1 fw-semibold">Key (slug)</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.key" placeholder="mobiles">
            </div>
            <div class="col-sm-5">
                <label class="form-label small mb-1 fw-semibold">Display Name</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.name" placeholder="Mobile Phones">
            </div>
            <div class="col-sm-2">
                <label class="form-label small mb-1 fw-semibold">Count</label>
                <input type="number" class="form-control form-control-sm" min="0"
                       wire:model="form.{{ $i }}.count" placeholder="24">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">WA Query</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.query" placeholder="Mobile Phones">
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
