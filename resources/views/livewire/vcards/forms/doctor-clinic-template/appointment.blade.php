{{--
 | doctor-clinic-template/appointment.blade.php
 | Appointment slots: defaultSlot + slots[{slot, session, time, availability, full}]
 | Form field labels/placeholders and success UI text are static — not shown.
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-calendar-plus me-1"></i>Appointment Slots
    </h6>
</div>

<div class="col-lg-4 mb-3">
    <label class="form-label fw-semibold" for="appt-def">Default / Pre-selected Slot</label>
    <input type="text" id="appt-def"
           class="form-control @error('form.defaultSlot') is-invalid @enderror"
           wire:model="form.defaultSlot" placeholder="10:00 AM">
    @error('form.defaultSlot') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

@php $slots = $form['slots'] ?? []; @endphp
@foreach($slots as $si => $slot)
<div class="col-12 mb-2" wire:key="appt-slot-{{ $si }}">
    <div class="border rounded-3 p-2 bg-light">
        <div class="row g-2 align-items-center">
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Slot</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.slots.{{ $si }}.slot" placeholder="10:00 AM">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Session</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.slots.{{ $si }}.session" placeholder="Morning">
            </div>
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Time Display</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.slots.{{ $si }}.time" placeholder="10:00 AM – 12:00 PM">
            </div>
            <div class="col-sm-2 d-flex align-items-end pb-1">
                <div class="form-check form-switch ms-1">
                    <input class="form-check-input" type="checkbox" role="switch"
                           id="aslot-full-{{ $si }}"
                           wire:model="form.slots.{{ $si }}.full">
                    <label class="form-check-label small" for="aslot-full-{{ $si }}">Full</label>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

{{-- Success message --}}
@php $success = $form['success'] ?? []; @endphp
@if(!empty($success['successMessage'] ?? ''))
<div class="col-12 mt-2 mb-3">
    <label class="form-label fw-semibold" for="appt-succ">Success Message</label>
    <input type="text" id="appt-succ"
           class="form-control @error('form.success.successMessage') is-invalid @enderror"
           wire:model="form.success.successMessage"
           placeholder="Your appointment has been booked!">
    @error('form.success.successMessage') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
@endif
