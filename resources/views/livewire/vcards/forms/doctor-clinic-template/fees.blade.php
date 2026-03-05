{{--
 | doctor-clinic-template/fees.blade.php
 | Consultation fee cards: {items:[{name, note, amount, oldAmount, icon, bg, color}], insuranceNote}
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-currency-inr me-1"></i>Consultation Fees
    </h6>
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="fee-ins">Insurance / Note</label>
    <input type="text" id="fee-ins"
           class="form-control @error('form.insuranceNote') is-invalid @enderror"
           wire:model="form.insuranceNote" placeholder="Insurance accepted · CGHS empanelled">
    @error('form.insuranceNote') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

@php $items = $form['items'] ?? []; @endphp
@foreach($items as $i => $fee)
<div class="col-12 mb-2" wire:key="fee-{{ $i }}">
    <div class="border rounded-3 p-2 bg-light">
        <div class="row g-2">
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Service Name</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.name" placeholder="OPD Consultation">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Amount</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.amount" placeholder="₹800">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Old Price</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.oldAmount" placeholder="₹1,200">
            </div>
            <div class="col-sm-2">
                <label class="form-label small mb-1 fw-semibold">Icon</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.icon" placeholder="🩺">
            </div>
            <div class="col-12">
                <label class="form-label small mb-1 fw-semibold">Note</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.note" placeholder="Includes follow-up">
            </div>
        </div>
    </div>
</div>
@endforeach
