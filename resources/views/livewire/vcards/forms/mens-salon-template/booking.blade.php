{{-- mens-salon-template/booking.blade.php — {defaultSlot, slots[], form.services[], form.barbers[], success{}} --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-calendar-check me-1"></i>Booking Slots
    </h6>
</div>
<div class="col-md-6">
    <label class="form-label small mb-1 fw-semibold">Default Slot</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.defaultSlot" placeholder="Morning: 9–12 PM">
</div>

<div class="col-12 mt-2">
    <label class="form-label small mb-1 fw-semibold">Time Slots</label>
    @if(is_array($form['slots'] ?? null))
    @foreach(($form['slots'] ?? []) as $si => $sl)
    <div class="border rounded-3 p-2 mb-1 bg-white" wire:key="mslot-{{ $si }}">
        <div class="row g-2 align-items-center">
            <div class="col-sm-3">
                <label class="form-label small mb-1">Slot Label</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.slots.{{ $si }}.slot" placeholder="Morning: 9–12 PM">
            </div>
            <div class="col-sm-2">
                <label class="form-label small mb-1">Session</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.slots.{{ $si }}.session" placeholder="Morning">
            </div>
            <div class="col-sm-2">
                <label class="form-label small mb-1">Time</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.slots.{{ $si }}.time" placeholder="9:00 – 12:00">
            </div>
            <div class="col-sm-2">
                <label class="form-label small mb-1">Availability</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.slots.{{ $si }}.availability" placeholder="3 slots left">
            </div>
            <div class="col-sm-1 d-flex align-items-end pb-1">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="mslot_full_{{ $si }}"
                           wire:model="form.slots.{{ $si }}.full">
                    <label class="form-check-label small" for="mslot_full_{{ $si }}">Full</label>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @endif
</div>

<div class="col-12 mt-2">
    <label class="form-label small mb-1 fw-semibold">Booking Form — Service Options</label>
    <p class="text-muted small mb-1">One service per row (used in booking dropdown)</p>
    @if(is_array($form['form']['services'] ?? null))
    @foreach(($form['form']['services'] ?? []) as $fsi => $fsvc)
    <input type="text" class="form-control form-control-sm mb-1"
           wire:model="form.form.services.{{ $fsi }}" wire:key="mfsvc-{{ $fsi }}"
           placeholder="Haircut (Regular)">
    @endforeach
    @endif
</div>

<div class="col-12 mt-2">
    <label class="form-label small mb-1 fw-semibold">Booking Form — Barber Options</label>
    @if(is_array($form['form']['barbers'] ?? null))
    @foreach(($form['form']['barbers'] ?? []) as $fbi => $fb)
    <input type="text" class="form-control form-control-sm mb-1"
           wire:model="form.form.barbers.{{ $fbi }}" wire:key="mfbar-{{ $fbi }}"
           placeholder="Rajesh – Senior Barber">
    @endforeach
    @endif
</div>

<div class="col-12 mt-3">
    <h6 class="fw-semibold text-muted text-uppercase mb-1" style="font-size:.7rem;letter-spacing:.07em;">
        Success Screen
    </h6>
</div>
<div class="col-md-6">
    <label class="form-label small mb-1 fw-semibold">Success Title</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.success.title" placeholder="Booking Request Sent! ✂️">
</div>
<div class="col-12">
    <label class="form-label small mb-1 fw-semibold">Success Message</label>
    <textarea class="form-control form-control-sm" rows="2"
              wire:model="form.success.text" placeholder="We've received your request…"></textarea>
</div>
