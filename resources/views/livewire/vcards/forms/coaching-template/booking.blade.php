{{--
 | coaching-template/booking.blade.php
 | Slot booking: defaultSlot + slots list [{slot, session, time, availability, full}]
--}}
@php $slots = $form['slots'] ?? []; @endphp

<div class="col-12 mb-3">
    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom">
        <span class="fw-semibold text-uppercase text-muted" style="font-size:.7rem;letter-spacing:.07em;">
            <i class="mdi mdi-calendar-clock-outline me-1"></i>Booking Slots
            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ count($slots) }}</span>
        </span>
        <button type="button" class="btn btn-sm btn-primary px-3"
                wire:click="addRowAndSave('slots',['slot','session','time','availability','full'])">
            <i class="mdi mdi-plus me-1"></i>Add Slot
        </button>
    </div>
</div>

{{-- Default slot --}}
<div class="col-lg-4 mb-3">
    <label class="form-label fw-semibold" for="bk-default">Default Slot</label>
    <input type="text" id="bk-default"
           class="form-control @error('form.defaultSlot') is-invalid @enderror"
           wire:model="form.defaultSlot" placeholder="10:00 AM">
    @error('form.defaultSlot') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

@if(empty($slots))
<div class="col-12">
    <div class="text-center py-4 rounded-3"
         style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);border:2px dashed #cbd5e1;">
        <i class="mdi mdi-calendar-plus fs-1 text-muted mb-2 d-block"></i>
        <p class="fw-semibold text-muted mb-2 small">No slots added yet</p>
        <button type="button" class="btn btn-sm btn-primary"
                wire:click="addRowAndSave('slots',['slot','session','time','availability','full'])">
            <i class="mdi mdi-plus me-1"></i>Add First Slot
        </button>
    </div>
</div>
@else
@foreach($slots as $si => $slot)
<div class="col-12 mb-2" wire:key="bk-slot-{{ $si }}">
    <div class="border rounded-3 p-3" style="background:#f8fafc;">
        <div class="row g-2 align-items-end">
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Slot Label</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.slots.{{ $si }}.slot"
                       placeholder="10:00 AM">
            </div>
            <div class="col-sm-2">
                <label class="form-label small mb-1 fw-semibold">Session</label>
                <select class="form-select form-select-sm"
                        wire:model="form.slots.{{ $si }}.session">
                    <option value="">— Session —</option>
                    <option>Morning</option>
                    <option>Afternoon</option>
                    <option>Noon</option>
                    <option>Evening</option>
                    <option>Night</option>
                </select>
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Time Display</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.slots.{{ $si }}.time"
                       placeholder="10:00 AM – 12:00 PM">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Availability</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.slots.{{ $si }}.availability"
                       placeholder="20 seats left">
            </div>
            <div class="col-sm-1 d-flex align-items-center gap-2 pt-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch"
                           id="slot-full-{{ $si }}"
                           wire:model="form.slots.{{ $si }}.full">
                    <label class="form-check-label small" for="slot-full-{{ $si }}">Full</label>
                </div>
            </div>
            <div class="col-sm-auto d-flex align-items-end pb-1">
                <button type="button"
                        class="btn btn-sm btn-outline-danger p-0 rounded-circle"
                        style="width:28px;height:28px;"
                        wire:click="removeRow('slots',{{ $si }})"
                        wire:confirm="Delete this slot?">
                    <i class="mdi mdi-delete" style="font-size:12px;"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
