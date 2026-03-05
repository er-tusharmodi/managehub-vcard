{{-- electronics-shop-template/hours.blade.php --}}
@php $rows = $form['rows'] ?? []; @endphp

<div class="col-12 mb-3">
    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom">
        <span class="fw-semibold text-uppercase text-muted" style="font-size:.7rem;letter-spacing:.07em;">
            <i class="mdi mdi-clock-outline me-1"></i>Business Hours
            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ count($rows) }}</span>
        </span>
        <button type="button" class="btn btn-sm btn-primary px-3"
                wire:click="addRowAndSave('rows',['day','time','rowClass'])">
            <i class="mdi mdi-plus me-1"></i>Add Row
        </button>
    </div>
</div>

{{-- Open Label --}}
@if(array_key_exists('openLabel', $form))
<div class="col-lg-5 mb-3">
    <label class="form-label small fw-semibold">Open Label <small class="text-muted">(e.g. Open Now)</small></label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.openLabel" placeholder="Open Now">
</div>
@endif

@if(empty($rows))
<div class="col-12">
    <div class="text-center py-4 rounded-3"
         style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);border:2px dashed #cbd5e1;">
        <i class="mdi mdi-clock-plus fs-1 text-muted mb-2 d-block"></i>
        <p class="fw-semibold text-muted mb-2 small">No rows added yet</p>
        <button type="button" class="btn btn-sm btn-primary"
                wire:click="addRowAndSave('rows',['day','time','rowClass'])">
            <i class="mdi mdi-plus me-1"></i>Add First Row
        </button>
    </div>
</div>
@else
@foreach($rows as $ri => $row)
<div class="col-12 mb-2" wire:key="elec-hours-row-{{ $ri }}">
    <div class="border rounded-3 p-3" style="background:#f8fafc;">
        <div class="row g-2 align-items-end">
            <div class="col-sm-3">
                <label class="form-label small fw-semibold mb-1">Day</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.rows.{{ $ri }}.day"
                       placeholder="Monday">
            </div>
            <div class="col-sm-5">
                <label class="form-label small fw-semibold mb-1">Hours / Time</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.rows.{{ $ri }}.time"
                       placeholder="10:00 AM – 9:00 PM or Closed">
            </div>
            <div class="col-sm-3">
                <label class="form-label small fw-semibold mb-1">Row Style</label>
                <select class="form-select form-select-sm"
                        wire:model="form.rows.{{ $ri }}.rowClass">
                    <option value="">Default</option>
                    <option value="open-row">Open</option>
                    <option value="closed">Closed</option>
                    <option value="today-row">Today</option>
                </select>
            </div>
            <div class="col-sm-1 d-flex align-items-end pb-1">
                <button type="button"
                        class="btn btn-sm btn-outline-danger p-0 rounded-circle"
                        style="width:28px;height:28px;"
                        wire:click="removeRowWithConfirm({{ $ri }}, 'rows')"
                        wire:confirm="Delete this row?">
                    <i class="mdi mdi-delete" style="font-size:12px;"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
