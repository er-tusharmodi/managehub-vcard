{{-- doctor-clinic-template/appointment.blade.php --}}
@php $slots = $form['slots'] ?? []; @endphp

<div class="col-12 mb-3">
    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom">
        <span class="fw-semibold text-uppercase text-muted" style="font-size:.7rem;letter-spacing:.07em;">
            <i class="mdi mdi-calendar-clock me-1"></i>Appointment Slots
            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ count($slots) }}</span>
        </span>
        <button type="button" class="btn btn-sm btn-primary px-3"
                wire:click="addRowAndSave('slots',['slot','session','time','availability','full'])">
            <i class="mdi mdi-plus me-1"></i>Add Slot
        </button>
    </div>
</div>

{{-- Default slot --}}
<div class="col-lg-5 mb-3">
    <label class="form-label small fw-semibold">Default Selected Slot</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.defaultSlot" placeholder="Morning: 9–11 AM">
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
<div class="col-12 mb-2" wire:key="appt-slot-{{ $si }}">
    <div class="border rounded-3 p-3" style="background:#f8fafc;">
        <div class="row g-2 align-items-end">
            <div class="col-sm-3">
                <label class="form-label small fw-semibold mb-1">Slot Label</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.slots.{{ $si }}.slot"
                       placeholder="Morning: 9–11 AM">
            </div>
            <div class="col-sm-2">
                <label class="form-label small fw-semibold mb-1">Session</label>
                <select class="form-select form-select-sm" wire:model="form.slots.{{ $si }}.session">
                    <option value="">— Session —</option>
                    <option>Morning</option>
                    <option>Afternoon</option>
                    <option>Noon</option>
                    <option>Evening</option>
                    <option>Night</option>
                </select>
            </div>
            <div class="col-sm-3">
                <label class="form-label small fw-semibold mb-1">Time Display</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.slots.{{ $si }}.time"
                       placeholder="9:00 – 11:00 AM">
            </div>
            <div class="col-sm-2">
                <label class="form-label small fw-semibold mb-1">Availability</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.slots.{{ $si }}.availability"
                       placeholder="4 slots left">
            </div>
            <div class="col-sm-2 d-flex align-items-end gap-3 pb-1">
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" role="switch"
                           id="aslot-full-{{ $si }}"
                           wire:model="form.slots.{{ $si }}.full">
                    <label class="form-check-label small" for="aslot-full-{{ $si }}">Full</label>
                </div>
                <button type="button"
                        class="btn btn-sm btn-outline-danger p-0 rounded-circle ms-auto"
                        style="width:28px;height:28px;flex-shrink:0;"
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

@php $success = $form['success'] ?? []; @endphp
@if(!empty($success['successMessage'] ?? ''))
<div class="col-12 mt-2">
    <label class="form-label small fw-semibold">Success Message</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.success.successMessage"
           placeholder="Your appointment has been booked!">
</div>
@endif
