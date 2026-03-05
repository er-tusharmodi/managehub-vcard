{{-- restaurant-cafe-template/reservation.blade.php --}}
{{-- times: [{label,value}] | guests: [{label,value}] | occasions: [{label,value}] | successTitle/Message --}}

<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-table-chair me-1"></i>Table Reservation
    </h6>
</div>

{{-- ─── Success Screen ───────────────────────────────────────────────────────────── --}}
<div class="col-12">
    <div class="border rounded-3 p-3 mb-1" style="background:#f8fafc;">
        <p class="fw-semibold text-muted text-uppercase mb-2" style="font-size:.68rem;letter-spacing:.06em;">
            <i class="mdi mdi-check-circle-outline me-1 text-success"></i>Success Screen
        </p>
        <div class="row g-2">
            <div class="col-md-5">
                <label class="form-label small mb-1 fw-semibold">Confirm Button Label</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.confirmLabel" placeholder="Confirm via WhatsApp">
            </div>
            <div class="col-md-7">
                <label class="form-label small mb-1 fw-semibold">Success Title</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.successTitle" placeholder="Reservation Requested! 🎉">
            </div>
            <div class="col-12">
                <label class="form-label small mb-1 fw-semibold">Success Message</label>
                <textarea class="form-control form-control-sm" rows="2"
                          wire:model="form.successMessage"
                          placeholder="We'll confirm your table via WhatsApp shortly."></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label small mb-1 fw-semibold">Success Button Label</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.successButton" placeholder="Make Another Reservation">
            </div>
        </div>
    </div>
</div>

{{-- ─── Form Labels ──────────────────────────────────────────────────────────────── --}}
<div class="col-12 mt-2">
    <div class="border rounded-3 p-3" style="background:#f8fafc;">
        <p class="fw-semibold text-muted text-uppercase mb-2" style="font-size:.68rem;letter-spacing:.06em;">
            <i class="mdi mdi-form-textbox me-1"></i>Form Field Labels
        </p>
        <div class="row g-2">
            @foreach(['name' => 'Name Field', 'phone' => 'Phone Field', 'date' => 'Date Field', 'time' => 'Time Field', 'guests' => 'Guests Field', 'occasion' => 'Occasion Field', 'note' => 'Note Field'] as $fk => $fl)
            <div class="col-md-4 col-sm-6">
                <label class="form-label small mb-1 fw-semibold">{{ $fl }}</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.labels.{{ $fk }}" placeholder="{{ $fl }}">
            </div>
            @endforeach
        </div>
        <div class="row g-2 mt-1">
            @foreach(['name' => 'Name Placeholder', 'phone' => 'Phone Placeholder', 'note' => 'Note Placeholder'] as $pk => $pl)
            <div class="col-md-4">
                <label class="form-label small mb-1 fw-semibold">{{ $pl }}</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.placeholders.{{ $pk }}" placeholder="{{ $pl }}">
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════════════ --}}
{{-- TIME SLOTS                                                                      --}}
{{-- ══════════════════════════════════════════════════════════════════════════════ --}}
<div class="col-12 mt-3">
    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom mb-2">
        <span class="fw-semibold text-uppercase text-muted" style="font-size:.7rem;letter-spacing:.07em;">
            <i class="mdi mdi-clock-outline me-1"></i>Available Time Slots
            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ count($form['times'] ?? []) }}</span>
        </span>
        <button type="button" class="btn btn-sm btn-primary px-3"
                wire:click="addRowAndSave('times', ['label','value'])">
            <i class="mdi mdi-plus me-1"></i>Add Slot
        </button>
    </div>

    @foreach(($form['times'] ?? []) as $ti => $t)
    <div class="input-group input-group-sm mb-1" wire:key="rt-time-{{ $ti }}">
        <span class="input-group-text text-muted" style="min-width:36px;">{{ $ti + 1 }}</span>
        <input type="text" class="form-control" wire:model="form.times.{{ $ti }}.label"
               placeholder="12:00 PM" style="max-width:200px;">
        <span class="input-group-text text-muted" style="font-size:.75rem;">value</span>
        <input type="text" class="form-control" wire:model="form.times.{{ $ti }}.value"
               placeholder="12:00 PM">
        <button type="button" class="btn btn-outline-danger"
                wire:click="removeRowWithConfirm({{ $ti }}, 'times')"
                wire:confirm="Remove this time slot?">
            <i class="mdi mdi-delete-outline" style="font-size:13px;"></i>
        </button>
    </div>
    @endforeach
</div>

{{-- ══════════════════════════════════════════════════════════════════════════════ --}}
{{-- GUEST COUNT OPTIONS                                                             --}}
{{-- ══════════════════════════════════════════════════════════════════════════════ --}}
<div class="col-12 mt-3">
    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom mb-2">
        <span class="fw-semibold text-uppercase text-muted" style="font-size:.7rem;letter-spacing:.07em;">
            <i class="mdi mdi-account-group-outline me-1"></i>Guest Count Options
            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ count($form['guests'] ?? []) }}</span>
        </span>
        <button type="button" class="btn btn-sm btn-primary px-3"
                wire:click="addRowAndSave('guests', ['label','value'])">
            <i class="mdi mdi-plus me-1"></i>Add Option
        </button>
    </div>

    @foreach(($form['guests'] ?? []) as $gi => $g)
    <div class="input-group input-group-sm mb-1" wire:key="rt-guest-{{ $gi }}">
        <span class="input-group-text text-muted" style="min-width:36px;">{{ $gi + 1 }}</span>
        <input type="text" class="form-control" wire:model="form.guests.{{ $gi }}.label"
               placeholder="4" style="max-width:160px;">
        <span class="input-group-text text-muted" style="font-size:.75rem;">value</span>
        <input type="text" class="form-control" wire:model="form.guests.{{ $gi }}.value"
               placeholder="4">
        <button type="button" class="btn btn-outline-danger"
                wire:click="removeRowWithConfirm({{ $gi }}, 'guests')"
                wire:confirm="Remove this guest option?">
            <i class="mdi mdi-delete-outline" style="font-size:13px;"></i>
        </button>
    </div>
    @endforeach
</div>

{{-- ══════════════════════════════════════════════════════════════════════════════ --}}
{{-- OCCASION OPTIONS                                                                --}}
{{-- ══════════════════════════════════════════════════════════════════════════════ --}}
<div class="col-12 mt-3 mb-3">
    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom mb-2">
        <span class="fw-semibold text-uppercase text-muted" style="font-size:.7rem;letter-spacing:.07em;">
            <i class="mdi mdi-party-popper me-1"></i>Occasion Options
            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ count($form['occasions'] ?? []) }}</span>
        </span>
        <button type="button" class="btn btn-sm btn-primary px-3"
                wire:click="addRowAndSave('occasions', ['label','value'])">
            <i class="mdi mdi-plus me-1"></i>Add Occasion
        </button>
    </div>

    @foreach(($form['occasions'] ?? []) as $oi => $occ)
    <div class="input-group input-group-sm mb-1" wire:key="rt-occ-{{ $oi }}">
        <span class="input-group-text text-muted" style="min-width:36px;">{{ $oi + 1 }}</span>
        <input type="text" class="form-control" wire:model="form.occasions.{{ $oi }}.label"
               placeholder="Birthday" style="max-width:200px;">
        <span class="input-group-text text-muted" style="font-size:.75rem;">value</span>
        <input type="text" class="form-control" wire:model="form.occasions.{{ $oi }}.value"
               placeholder="Birthday">
        <button type="button" class="btn btn-outline-danger"
                wire:click="removeRowWithConfirm({{ $oi }}, 'occasions')"
                wire:confirm="Remove this occasion?">
            <i class="mdi mdi-delete-outline" style="font-size:13px;"></i>
        </button>
    </div>
    @endforeach
</div>
