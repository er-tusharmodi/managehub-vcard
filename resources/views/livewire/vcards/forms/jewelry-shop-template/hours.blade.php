{{--
 | jewelry-shop-template/hours.blade.php
 | Business hours for jewelry-shop-template.
 | Format: LIST of {day, time, today: bool}
 | (different from _shared/hours which expects a dict with a "rows" key)
--}}

<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-clock-time-four-outline me-1"></i>Business Hours
    </h6>
    <small class="text-muted">Mark "Today" to highlight the current day for visitors.</small>
</div>

@if(is_array($form) && array_values($form) === $form)
    {{-- flat list --}}
    @foreach($form as $ri => $row)
    <div class="col-12 mb-1">
        <div class="border rounded p-2 {{ ($row['today'] ?? false) ? 'border-warning bg-warning bg-opacity-10' : 'bg-light' }}">
            <div class="row g-2 align-items-center">
                <div class="col-sm-4">
                    <label class="form-label small mb-1 fw-semibold">Day</label>
                    <input type="text"
                           class="form-control form-control-sm"
                           wire:model="form.{{ $ri }}.day"
                           placeholder="Monday">
                </div>
                <div class="col-sm-5">
                    <label class="form-label small mb-1 fw-semibold">Timings</label>
                    <input type="text"
                           class="form-control form-control-sm"
                           wire:model="form.{{ $ri }}.time"
                           placeholder="10:00 AM – 8:00 PM">
                </div>
                <div class="col-sm-3 d-flex align-items-end pb-1">
                    <div class="form-check form-switch ms-1">
                        <input class="form-check-input"
                               type="checkbox"
                               role="switch"
                               id="today-{{ $ri }}"
                               wire:model="form.{{ $ri }}.today">
                        <label class="form-check-label small" for="today-{{ $ri }}">Today</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@else
    <div class="col-12">
        <p class="text-warning small"><i class="mdi mdi-alert me-1"></i>Unexpected hours format — please check template data.</p>
    </div>
@endif
