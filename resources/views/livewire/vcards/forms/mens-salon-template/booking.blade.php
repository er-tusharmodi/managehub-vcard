{{-- mens-salon-template/booking.blade.php --}}
{{-- slots: [{slot,session,time,availability,full?,fullLabel?}] | form.services: string[] | form.barbers: string[] --}}

@php
    $slots    = $form['slots']   ?? [];
    $services = $form['form']['services'] ?? [];
    $barbers  = $form['form']['barbers']  ?? [];
@endphp

{{-- ─── Section heading ─────────────────────────────────────────────────────── --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-calendar-clock me-1"></i>Booking Form
    </h6>
</div>

{{-- ─── Success screen ───────────────────────────────────────────────────────── --}}
<div class="col-12">
    <div class="border rounded-3 p-3 mb-1" style="background:#f8fafc;">
        <p class="fw-semibold text-muted text-uppercase mb-2" style="font-size:.68rem;letter-spacing:.06em;">
            <i class="mdi mdi-check-circle-outline me-1 text-success"></i>Success Screen
        </p>
        <div class="row g-2">
            <div class="col-md-6">
                <label class="form-label small mb-1 fw-semibold">Success Title</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.success.title" placeholder="Booking Confirmed! 🎉">
            </div>
            <div class="col-12">
                <label class="form-label small mb-1 fw-semibold">Success Message</label>
                <textarea class="form-control form-control-sm" rows="2"
                          wire:model="form.success.text"
                          placeholder="We'll confirm your slot via WhatsApp shortly."></textarea>
            </div>
        </div>
    </div>
</div>

{{-- ─── Default slot ─────────────────────────────────────────────────────────── --}}
<div class="col-md-5">
    <label class="form-label small mb-1 fw-semibold">Default Selected Slot</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.defaultSlot" placeholder="10:00 AM">
</div>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- TIME SLOTS                                                                  --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div class="col-12 mt-2">
    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom mb-2">
        <span class="fw-semibold text-uppercase text-muted" style="font-size:.7rem;letter-spacing:.07em;">
            <i class="mdi mdi-clock-outline me-1"></i>Time Slots
            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ count($slots) }}</span>
        </span>
        <button type="button" class="btn btn-sm btn-primary px-3"
                data-bs-toggle="modal" data-bs-target="#addSlotModal">
            <i class="mdi mdi-plus me-1"></i>Add Slot
        </button>
    </div>

    @if(!empty($slots))
    <div class="table-responsive border rounded-3 overflow-hidden">
        <table class="table table-sm table-hover mb-0" style="font-size:.8rem;">
            <thead class="table-light">
                <tr>
                    <th>Slot</th>
                    <th>Session</th>
                    <th>Time Label</th>
                    <th>Availability</th>
                    <th class="text-center">Full?</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($slots as $si => $slot)
                <tr wire:key="ms-slot-{{ $si }}">
                    <td>
                        <input type="text" class="form-control form-control-sm border-0 bg-transparent"
                               wire:model="form.slots.{{ $si }}.slot" placeholder="Morning">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm border-0 bg-transparent"
                               wire:model="form.slots.{{ $si }}.session" placeholder="AM">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm border-0 bg-transparent"
                               wire:model="form.slots.{{ $si }}.time" placeholder="10:00 AM – 1:00 PM">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm border-0 bg-transparent"
                               wire:model="form.slots.{{ $si }}.availability" placeholder="8 slots left">
                    </td>
                    <td class="text-center align-middle">
                        <div class="form-check form-switch d-flex justify-content-center mb-0">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   wire:model="form.slots.{{ $si }}.full">
                        </div>
                    </td>
                    <td class="text-end align-middle">
                        <button type="button"
                                class="btn btn-sm btn-outline-danger p-0 rounded-circle"
                                style="width:26px;height:26px;"
                                wire:click="removeRowWithConfirm({{ $si }}, 'slots')"
                                wire:confirm="Delete this time slot?">
                            <i class="mdi mdi-delete" style="font-size:11px;"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-4 rounded-3" style="border:2px dashed #cbd5e1;background:#f8fafc;">
        <i class="mdi mdi-clock-outline fs-2 text-muted mb-1 d-block"></i>
        <p class="text-muted small mb-0">No time slots added yet.</p>
    </div>
    @endif
</div>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- SERVICE OPTIONS                                                             --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div class="col-12 mt-3">
    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom mb-2">
        <span class="fw-semibold text-uppercase text-muted" style="font-size:.7rem;letter-spacing:.07em;">
            <i class="mdi mdi-scissors-cutting me-1"></i>Service Options (Dropdown)
            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ count($services) }}</span>
        </span>
    </div>

    @foreach($services as $svi => $svc)
    <div class="input-group input-group-sm mb-1" wire:key="ms-svc-{{ $svi }}">
        <span class="input-group-text text-muted" style="font-size:.75rem;">{{ $svi + 1 }}</span>
        <input type="text" class="form-control form-control-sm"
               wire:model="form.form.services.{{ $svi }}" placeholder="Haircut (Regular)">
        <button type="button" class="btn btn-outline-danger"
                wire:click="removeRowWithConfirm({{ $svi }}, 'form.services')"
                wire:confirm="Remove this service option?">
            <i class="mdi mdi-delete-outline" style="font-size:13px;"></i>
        </button>
    </div>
    @endforeach

    <div class="input-group input-group-sm mt-2">
        <input type="text" class="form-control form-control-sm"
               wire:model="newItem.svc" placeholder="New service option…"
               wire:keydown.enter.prevent="addStringAndSave('form.services', 'svc')">
        <button type="button" class="btn btn-primary"
                wire:click="addStringAndSave('form.services', 'svc')">
            <i class="mdi mdi-plus"></i> Add
        </button>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- BARBER OPTIONS                                                              --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div class="col-12 mt-3 mb-3">
    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom mb-2">
        <span class="fw-semibold text-uppercase text-muted" style="font-size:.7rem;letter-spacing:.07em;">
            <i class="mdi mdi-account-tie me-1"></i>Barber Options (Dropdown)
            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ count($barbers) }}</span>
        </span>
    </div>

    @foreach($barbers as $bri => $barber)
    <div class="input-group input-group-sm mb-1" wire:key="ms-barber-{{ $bri }}">
        <span class="input-group-text text-muted" style="font-size:.75rem;">{{ $bri + 1 }}</span>
        <input type="text" class="form-control form-control-sm"
               wire:model="form.form.barbers.{{ $bri }}" placeholder="Rajesh – Senior Barber">
        <button type="button" class="btn btn-outline-danger"
                wire:click="removeRowWithConfirm({{ $bri }}, 'form.barbers')"
                wire:confirm="Remove this barber option?">
            <i class="mdi mdi-delete-outline" style="font-size:13px;"></i>
        </button>
    </div>
    @endforeach

    <div class="input-group input-group-sm mt-2">
        <input type="text" class="form-control form-control-sm"
               wire:model="newItem.barber" placeholder="New barber option…"
               wire:keydown.enter.prevent="addStringAndSave('form.barbers', 'barber')">
        <button type="button" class="btn btn-primary"
                wire:click="addStringAndSave('form.barbers', 'barber')">
            <i class="mdi mdi-plus"></i> Add
        </button>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════ --}}
{{-- ADD SLOT MODAL                                                              --}}
{{-- ══════════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="addSlotModal" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title fw-semibold">Add Time Slot</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-2">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold mb-1">Slot Name</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="newItem.slot" placeholder="Morning">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold mb-1">Session (AM/PM)</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="newItem.session" placeholder="AM">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold mb-1">Time Label</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="newItem.time" placeholder="10:00 AM – 1:00 PM">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold mb-1">Availability Text</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="newItem.availability" placeholder="8 slots left">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold mb-1">Full Label (shown when full)</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="newItem.fullLabel" placeholder="Fully Booked">
                    </div>
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   id="newSlotFull" wire:model="newItem.full">
                            <label class="form-check-label small fw-semibold" for="newSlotFull">Mark as Full</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-sm btn-primary"
                        wire:click="addRowAndSave('slots', ['slot','session','time','availability','full','fullLabel'])"
                        x-on:close-modal.window="document.getElementById('addSlotModal') && bootstrap.Modal.getOrCreateInstance(document.getElementById('addSlotModal')).hide()">
                    <i class="mdi mdi-check me-1"></i>Add Slot
                </button>
            </div>
        </div>
    </div>
</div>
