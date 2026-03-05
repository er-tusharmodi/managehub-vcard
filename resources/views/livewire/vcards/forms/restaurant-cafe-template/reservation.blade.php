{{-- restaurant-cafe-template/reservation.blade.php — {times[], guests[], occasions[], successTitle, successMessage} --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-table-chair me-1"></i>Table Reservation
    </h6>
</div>

{{-- Success Screen --}}
<div class="col-md-6">
    <label class="form-label small mb-1 fw-semibold">Success Title</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.successTitle" placeholder="Reservation Confirmed! 🎉">
</div>
<div class="col-12">
    <label class="form-label small mb-1 fw-semibold">Success Message</label>
    <textarea class="form-control form-control-sm" rows="2"
              wire:model="form.successMessage" placeholder="Your table has been reserved. We'll confirm on WhatsApp shortly."></textarea>
</div>

{{-- Time slots --}}
<div class="col-12 mt-2">
    <label class="form-label small mb-1 fw-semibold">Available Time Slots</label>
    @if(is_array($form['times'] ?? null))
    @foreach(($form['times'] ?? []) as $ti => $t)
    <div class="input-group input-group-sm mb-1" wire:key="rtime-{{ $ti }}">
        @if(is_array($t))
        <input type="text" class="form-control form-control-sm"
               wire:model="form.times.{{ $ti }}.label" placeholder="Label">
        <input type="text" class="form-control form-control-sm"
               wire:model="form.times.{{ $ti }}.value" placeholder="Value">
        @else
        <input type="text" class="form-control form-control-sm"
               wire:model="form.times.{{ $ti }}" placeholder="7:00 PM">
        @endif
    </div>
    @endforeach
    @endif
</div>

{{-- Guest options --}}
<div class="col-12 mt-2">
    <label class="form-label small mb-1 fw-semibold">Guest Count Options</label>
    @if(is_array($form['guests'] ?? null))
    @foreach(($form['guests'] ?? []) as $gi => $g)
    <div class="input-group input-group-sm mb-1" wire:key="rguest-{{ $gi }}">
        @if(is_array($g))
        <span class="input-group-text small">Label</span>
        <input type="text" class="form-control" wire:model="form.guests.{{ $gi }}.label" placeholder="4">
        <span class="input-group-text small">Value</span>
        <input type="text" class="form-control" wire:model="form.guests.{{ $gi }}.value" placeholder="4">
        @else
        <input type="text" class="form-control form-control-sm"
               wire:model="form.guests.{{ $gi }}" placeholder="4 guests">
        @endif
    </div>
    @endforeach
    @endif
</div>

{{-- Occasions --}}
<div class="col-12 mt-2">
    <label class="form-label small mb-1 fw-semibold">Occasion Options</label>
    @if(is_array($form['occasions'] ?? null))
    @foreach(($form['occasions'] ?? []) as $oi => $occ)
    <div class="input-group input-group-sm mb-1" wire:key="rocc-{{ $oi }}">
        @if(is_array($occ))
        <span class="input-group-text small">Label</span>
        <input type="text" class="form-control" wire:model="form.occasions.{{ $oi }}.label" placeholder="Birthday">
        <span class="input-group-text small">Value</span>
        <input type="text" class="form-control" wire:model="form.occasions.{{ $oi }}.value" placeholder="birthday">
        @else
        <input type="text" class="form-control form-control-sm"
               wire:model="form.occasions.{{ $oi }}" placeholder="Birthday">
        @endif
    </div>
    @endforeach
    @endif
</div>
