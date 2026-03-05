{{--
 | coaching-template/demo.blade.php
 | Demo booking section: promoText, slots[], examOptions[], educationOptions[]
 | Form field labels and success messages are static — not shown here.
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-video-outline me-1"></i>Free Demo Class
    </h6>
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="demo-promo">Promo Text</label>
    <textarea id="demo-promo" rows="2"
              class="form-control @error('form.promoText') is-invalid @enderror"
              wire:model="form.promoText"
              placeholder="Book your free demo class today..."></textarea>
    @error('form.promoText') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Slots --}}
@php $slots = $form['slots'] ?? []; @endphp
@if(!empty($slots))
<div class="col-12 mb-1">
    <label class="form-label fw-semibold text-muted">Demo Time Slots</label>
</div>
@foreach($slots as $si => $slot)
<div class="col-lg-3 mb-2" wire:key="demoslot-{{ $si }}">
    <input type="text" class="form-control form-control-sm"
           wire:model="form.slots.{{ $si }}" placeholder="10:00 AM">
</div>
@endforeach
@endif

{{-- Exam Options --}}
@php $examOpts = $form['examOptions'] ?? []; @endphp
@if(!empty($examOpts))
<div class="col-12 mt-2 mb-1">
    <label class="form-label fw-semibold text-muted">Exam Options (dropdown)</label>
</div>
@foreach($examOpts as $ei => $exam)
<div class="col-lg-4 mb-2" wire:key="exam-{{ $ei }}">
    <input type="text" class="form-control form-control-sm"
           wire:model="form.examOptions.{{ $ei }}" placeholder="UPSC CSE">
</div>
@endforeach
@endif

{{-- Education Options --}}
@php $eduOpts = $form['educationOptions'] ?? []; @endphp
@if(!empty($eduOpts))
<div class="col-12 mt-2 mb-1">
    <label class="form-label fw-semibold text-muted">Education Options (dropdown)</label>
</div>
@foreach($eduOpts as $edi => $edu)
<div class="col-lg-4 mb-2" wire:key="edu-{{ $edi }}">
    <input type="text" class="form-control form-control-sm"
           wire:model="form.educationOptions.{{ $edi }}" placeholder="Graduate">
</div>
@endforeach
@endif
