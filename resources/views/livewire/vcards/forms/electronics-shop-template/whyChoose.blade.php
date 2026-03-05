{{--
 | electronics-shop-template/whyChoose.blade.php
 | Why Choose Us chips: [{text, tone, icon}]
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-star-circle-outline me-1"></i>Why Choose Us — Chips
    </h6>
</div>

@if(is_array($form))
@foreach($form as $i => $item)
<div class="col-12 mb-2" wire:key="wc-{{ $i }}">
    <div class="border rounded-3 p-2 bg-light">
        <div class="row g-2 align-items-center">
            <div class="col-sm-5">
                <label class="form-label small mb-1 fw-semibold">Feature Text</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.text" placeholder="Authorised Service Centre">
            </div>
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Tone / Color</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.tone" placeholder="blue">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Icon</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.icon" placeholder="🔧">
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
