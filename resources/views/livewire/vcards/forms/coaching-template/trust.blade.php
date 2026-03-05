{{--
 | coaching-template/trust.blade.php
 | Trust / USP chips: {items: [{iconClass, text}]}
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-shield-check-outline me-1"></i>Trust Points
    </h6>
</div>

@php $items = $form['items'] ?? []; @endphp
@foreach($items as $i => $item)
<div class="col-12 mb-2" wire:key="trust-{{ $i }}">
    <div class="border rounded-3 p-2 bg-light">
        <div class="row g-2 align-items-center">
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Icon Class</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.iconClass"
                       placeholder="bi-check-circle">
            </div>
            <div class="col-sm-8">
                <label class="form-label small mb-1 fw-semibold">Text</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.text"
                       placeholder="Expert Faculty">
            </div>
        </div>
    </div>
</div>
@endforeach
