{{--
 | coaching-template/booking.blade.php
 | Slot booking: defaultSlot + slots list [{slot, session, time, availability, full, fullLabel(hidden)}]
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-calendar-check-outline me-1"></i>Booking Slots
    </h6>
</div>

<div class="col-lg-4 mb-3">
    <label class="form-label fw-semibold" for="bk-default">Default Slot</label>
    <input type="text" id="bk-default"
           class="form-control @error('form.defaultSlot') is-invalid @enderror"
           wire:model="form.defaultSlot" placeholder="10:00 AM">
    @error('form.defaultSlot') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-12 mb-1">
    <label class="form-label fw-semibold text-muted">Available Slots</label>
</div>

@php $slots = $form['slots'] ?? []; @endphp
@foreach($slots as $si => $slot)
<div class="col-12 mb-2" wire:key="slot-{{ $si }}">
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
                           id="slot-full-{{ $si }}"
                           wire:model="form.slots.{{ $si }}.full">
                    <label class="form-check-label small" for="slot-full-{{ $si }}">Full</label>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
