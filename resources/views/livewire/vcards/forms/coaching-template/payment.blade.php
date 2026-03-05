{{--
 | coaching-template/payment.blade.php
 | Payment methods: items[{iconClass, name, detail}]
 | (key is 'payment' not 'payments' — no _shared match)
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-credit-card-outline me-1"></i>Payment Methods
    </h6>
</div>

@php $items = $form['items'] ?? []; @endphp
@foreach($items as $i => $pm)
<div class="col-12 mb-2" wire:key="pm-{{ $i }}">
    <div class="border rounded-3 p-2 bg-light">
        <div class="row g-2 align-items-center">
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Icon Class</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.iconClass" placeholder="bi-wallet2">
            </div>
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Name</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.name" placeholder="UPI">
            </div>
            <div class="col-sm-5">
                <label class="form-label small mb-1 fw-semibold">Detail</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.detail" placeholder="Google Pay, PhonePe, Paytm">
            </div>
        </div>
    </div>
</div>
@endforeach
