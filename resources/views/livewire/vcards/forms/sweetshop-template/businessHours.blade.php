{{-- sweetshop-template/businessHours.blade.php --}}
{{-- Shape: {badge, suggestLabel, days:[{day, time, open:bool}]} --}}

<div class="col-12 mb-2">
    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom mb-2">
        <span class="fw-semibold text-uppercase text-muted" style="font-size:.7rem;letter-spacing:.07em;">
            <i class="mdi mdi-clock-time-four-outline me-1"></i>Business Hours
        </span>
        <button type="button" class="btn btn-sm btn-primary px-3"
                wire:click="addRowAndSave('days', ['day','time','open'])">
            <i class="mdi mdi-plus me-1"></i>Add Row
        </button>
    </div>
</div>

<div class="col-md-5">
    <label class="form-label small mb-1 fw-semibold">Status Badge</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.badge" placeholder="Open Now">
</div>
<div class="col-md-7">
    <label class="form-label small mb-1 fw-semibold">Suggest Hours Label</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.suggestLabel" placeholder="Suggest new hours">
</div>

<div class="col-12 mt-2">
    @if(!empty($form['days'] ?? []))
    <div class="table-responsive border rounded-3 overflow-hidden">
        <table class="table table-sm table-hover mb-0" style="font-size:.8rem;">
            <thead class="table-light">
                <tr>
                    <th style="width:30px;">#</th>
                    <th>Day</th>
                    <th>Hours</th>
                    <th class="text-center" style="width:60px;">Open?</th>
                    <th class="text-center" style="width:70px;">Move</th>
                    <th style="width:40px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach(($form['days'] ?? []) as $di => $dayRow)
                <tr wire:key="ss-bh-{{ $di }}"
                    class="{{ ($dayRow['open'] ?? true) ? '' : 'table-secondary' }}">
                    <td class="text-muted align-middle">{{ $di + 1 }}</td>
                    <td>
                        <input type="text" class="form-control form-control-sm border-0 bg-transparent"
                               wire:model="form.days.{{ $di }}.day"
                               placeholder="Monday">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm border-0 bg-transparent"
                               wire:model="form.days.{{ $di }}.time"
                               placeholder="9:00 AM – 7:00 PM">
                    </td>
                    <td class="text-center align-middle">
                        <div class="form-check form-switch d-flex justify-content-center mb-0">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   wire:model="form.days.{{ $di }}.open">
                        </div>
                    </td>
                    <td class="text-center align-middle">
                        <div class="d-flex gap-1 justify-content-center">
                            <button type="button" class="btn btn-xs btn-outline-secondary p-0"
                                    style="width:22px;height:22px;"
                                    wire:click="moveRow('days', {{ $di }}, -1)"
                                    {{ $di === 0 ? 'disabled' : '' }}>
                                <i class="mdi mdi-arrow-up" style="font-size:11px;"></i>
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-secondary p-0"
                                    style="width:22px;height:22px;"
                                    wire:click="moveRow('days', {{ $di }}, 1)"
                                    {{ $di === count($form['days']) - 1 ? 'disabled' : '' }}>
                                <i class="mdi mdi-arrow-down" style="font-size:11px;"></i>
                            </button>
                        </div>
                    </td>
                    <td class="align-middle">
                        <button type="button"
                                class="btn btn-sm btn-outline-danger p-0 rounded-circle"
                                style="width:26px;height:26px;"
                                wire:click="removeRowWithConfirm({{ $di }}, 'days')"
                                wire:confirm="Remove this hours row?">
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
        <p class="text-muted small mb-0">No hours rows yet.</p>
    </div>
    @endif
</div>
