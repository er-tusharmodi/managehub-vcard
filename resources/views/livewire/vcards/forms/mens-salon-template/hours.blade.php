{{-- mens-salon-template/hours.blade.php — {today, rows:[{day,time,rowClass?}]} --}}

<div class="col-12 mb-2">
    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom mb-2">
        <span class="fw-semibold text-uppercase text-muted" style="font-size:.7rem;letter-spacing:.07em;">
            <i class="mdi mdi-clock-outline me-1"></i>Business Hours
        </span>
        <button type="button" class="btn btn-sm btn-primary px-3"
                wire:click="addRowAndSave('rows', ['day','time','rowClass'])">
            <i class="mdi mdi-plus me-1"></i>Add Row
        </button>
    </div>
</div>

<div class="col-md-8">
    <label class="form-label small mb-1 fw-semibold">Today Status Label</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.today" placeholder="Open Now · Closes 9:00 PM">
</div>

<div class="col-12 mt-2">
    @if(!empty($form['rows'] ?? []))
    <div class="table-responsive border rounded-3 overflow-hidden">
        <table class="table table-sm table-hover mb-0" style="font-size:.8rem;">
            <thead class="table-light">
                <tr>
                    <th style="width:30px;">#</th>
                    <th>Day</th>
                    <th>Hours</th>
                    <th>Row Style</th>
                    <th class="text-center" style="width:70px;">Move</th>
                    <th style="width:40px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach(($form['rows'] ?? []) as $ri => $row)
                <tr wire:key="ms-hr-{{ $ri }}">
                    <td class="text-muted align-middle">{{ $ri + 1 }}</td>
                    <td>
                        <input type="text" class="form-control form-control-sm border-0 bg-transparent"
                               wire:model="form.rows.{{ $ri }}.day" placeholder="Monday">
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm border-0 bg-transparent"
                               wire:model="form.rows.{{ $ri }}.time" placeholder="10:00 AM – 9:00 PM">
                    </td>
                    <td>
                        <select class="form-select form-select-sm border-0 bg-transparent"
                                wire:model="form.rows.{{ $ri }}.rowClass">
                            <option value="">Default</option>
                            <option value="open-row">open-row (highlight today)</option>
                            <option value="closed-row">closed-row (greyed)</option>
                        </select>
                    </td>
                    <td class="text-center align-middle">
                        <div class="d-flex gap-1 justify-content-center">
                            <button type="button" class="btn btn-xs btn-outline-secondary p-0" style="width:22px;height:22px;"
                                    wire:click="moveRow('rows', {{ $ri }}, -1)" {{ $ri === 0 ? 'disabled' : '' }}>
                                <i class="mdi mdi-arrow-up" style="font-size:11px;"></i>
                            </button>
                            <button type="button" class="btn btn-xs btn-outline-secondary p-0" style="width:22px;height:22px;"
                                    wire:click="moveRow('rows', {{ $ri }}, 1)" {{ $ri === count($form['rows']) - 1 ? 'disabled' : '' }}>
                                <i class="mdi mdi-arrow-down" style="font-size:11px;"></i>
                            </button>
                        </div>
                    </td>
                    <td class="align-middle">
                        <button type="button"
                                class="btn btn-sm btn-outline-danger p-0 rounded-circle"
                                style="width:26px;height:26px;"
                                wire:click="removeRowWithConfirm({{ $ri }}, 'rows')"
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
