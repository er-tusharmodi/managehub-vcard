{{-- doctor-clinic-template/hours.blade.php --}}
@php $rows = $form['rows'] ?? []; @endphp

<div class="col-12 mb-3">
    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom">
        <span class="fw-semibold text-uppercase text-muted" style="font-size:.7rem;letter-spacing:.07em;">
            <i class="mdi mdi-clock-outline me-1"></i>Clinic Hours
            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ count($rows) }}</span>
        </span>
        <button type="button" class="btn btn-sm btn-primary px-3"
                wire:click="addRowAndSave('rows',['day','session','time','rowClass'])">
            <i class="mdi mdi-plus me-1"></i>Add Row
        </button>
    </div>
</div>

@if(isset($form['todayLabel']))
<div class="col-12 mb-3">
    <label class="form-label small fw-semibold">Today's Label <small class="text-muted">(shown on card)</small></label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.todayLabel"
           placeholder="Today: Monday — Open till 8:30 PM">
</div>
@endif

@if(empty($rows))
<div class="col-12">
    <div class="text-center py-4 rounded-3"
         style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);border:2px dashed #cbd5e1;">
        <i class="mdi mdi-clock-plus fs-1 text-muted mb-2 d-block"></i>
        <p class="fw-semibold text-muted mb-2 small">No rows added yet</p>
        <button type="button" class="btn btn-sm btn-primary"
                wire:click="addRowAndSave('rows',['day','session','time','rowClass'])">
            <i class="mdi mdi-plus me-1"></i>Add First Row
        </button>
    </div>
</div>
@else
@foreach($rows as $ri => $row)
<div class="col-12 mb-2" wire:key="hours-row-{{ $ri }}">
    <div class="border rounded-3 p-3" style="background:#f8fafc;">
        <div class="row g-2 align-items-end">
            <div class="col-sm-3">
                <label class="form-label small fw-semibold mb-1">Day</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.rows.{{ $ri }}.day"
                       placeholder="Monday – Friday">
            </div>
            <div class="col-sm-2">
                <label class="form-label small fw-semibold mb-1">Session</label>
                <select class="form-select form-select-sm" wire:model="form.rows.{{ $ri }}.session">
                    <option value="">—</option>
                    <option>Morning</option>
                    <option>Afternoon</option>
                    <option>Evening</option>
                    <option>Night</option>
                    <option>Emergency</option>
                </select>
            </div>
            <div class="col-sm-4">
                <label class="form-label small fw-semibold mb-1">Time</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.rows.{{ $ri }}.time"
                       placeholder="9:00 – 11:30 AM or Closed">
            </div>
            <div class="col-sm-2">
                <label class="form-label small fw-semibold mb-1">Row Style</label>
                <select class="form-select form-select-sm" wire:model="form.rows.{{ $ri }}.rowClass">
                    <option value="">Default</option>
                    <option value="open-row">Open</option>
                    <option value="closed">Closed</option>
                    <option value="emergency">Emergency</option>
                </select>
            </div>
            <div class="col-sm-1 d-flex align-items-end pb-1">
                <button type="button"
                        class="btn btn-sm btn-outline-danger p-0 rounded-circle"
                        style="width:28px;height:28px;"
                        wire:click="removeRow('rows',{{ $ri }})"
                        wire:confirm="Delete this row?">
                    <i class="mdi mdi-delete" style="font-size:12px;"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
