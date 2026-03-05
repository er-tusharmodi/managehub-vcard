{{--
 | coaching-template/fees.blade.php
 | Fee structure: items[{name, note, amount, oldAmount, iconClass, color, bg}]
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-currency-inr me-1"></i>Fee Structure
    </h6>
</div>

@php $items = $form['items'] ?? []; @endphp
@foreach($items as $i => $fee)
<div class="col-12 mb-2" wire:key="fee-{{ $i }}">
    <div class="border rounded-3 p-3 bg-light">
        <div class="row g-2">
            <div class="col-sm-5">
                <label class="form-label small mb-1 fw-semibold">Programme Name</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.name" placeholder="UPSC Foundation">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Amount</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.amount" placeholder="₹8,500">
            </div>
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Old / Strikethrough Price</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.oldAmount" placeholder="₹12,000">
            </div>
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Icon Class</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.iconClass" placeholder="bi-mortarboard">
            </div>
            <div class="col-sm-8">
                <label class="form-label small mb-1 fw-semibold">Note / Sub-text</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.note" placeholder="per month, inclusive of materials">
            </div>
        </div>
    </div>
</div>
@endforeach
