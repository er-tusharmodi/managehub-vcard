{{--
 | sweetshop-template/businessHours.blade.php
 | Business hours for sweetshop-template.
 | Format: dict { badge, suggestLabel, days: [ {day, time, open: bool} ] }
 | (uses "days" key — _shared/hours expects "rows", so this template needs its own partial)
--}}

<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-clock-time-four-outline me-1"></i>Business Hours
    </h6>
</div>

{{-- Days rows --}}
@if(isset($form['days']) && is_array($form['days']))
<div class="col-12 mb-1">
    <label class="form-label fw-semibold text-muted">Weekly Schedule</label>
</div>
@foreach($form['days'] as $di => $dayRow)
<div class="col-12 mb-1">
    <div class="border rounded p-2 {{ ($dayRow['open'] ?? true) ? 'bg-light' : 'bg-secondary bg-opacity-10 border-secondary' }}">
        <div class="row g-2 align-items-center">
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Day</label>
                <input type="text"
                       class="form-control form-control-sm"
                       wire:model="form.days.{{ $di }}.day"
                       placeholder="Monday">
            </div>
            <div class="col-sm-5">
                <label class="form-label small mb-1 fw-semibold">Timings</label>
                <input type="text"
                       class="form-control form-control-sm"
                       wire:model="form.days.{{ $di }}.time"
                       placeholder="10:00 AM – 9:00 PM">
            </div>
            <div class="col-sm-3 d-flex align-items-end pb-1">
                <div class="form-check form-switch ms-1">
                    <input class="form-check-input"
                           type="checkbox"
                           role="switch"
                           id="bh-open-{{ $di }}"
                           wire:model="form.days.{{ $di }}.open">
                    <label class="form-check-label small" for="bh-open-{{ $di }}">Open</label>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
