{{-- doctor-clinic-template/hours.blade.php --}}
{{-- form[0]=todayLabel string, form[1]=[{day,session,time,rowClass}] --}}
@php
    $rows = is_array($form[1] ?? null) ? $form[1] : [];
    $rowClassLabels = ['' => 'Default', 'open-row' => 'Open', 'closed' => 'Closed', 'emergency' => 'Emergency'];
    $rowClassColors = ['' => '#64748b', 'open-row' => '#166534', 'closed' => '#991b1b', 'emergency' => '#92400e'];
    $rowClassBg     = ['' => '#f1f5f9',  'open-row' => '#dcfce7', 'closed' => '#fee2e2',  'emergency' => '#fef3c7'];
    $defaultItem = ['day'=>'','session'=>'Morning','time'=>'','rowClass'=>''];
@endphp

{{-- ── Header ──────────────────────────────────────────────────── --}}
<div class="col-12 mb-2">
    <div class="border rounded-3 overflow-hidden shadow-sm">
        <div class="d-flex align-items-center justify-content-between px-3 py-2"
             style="background:linear-gradient(90deg,#f0f9ff,#e0f2fe);">
            <span class="fw-semibold d-flex align-items-center gap-2" style="font-size:.85rem;color:#0369a1;">
                <i class="mdi mdi-clock-outline"></i>
                Clinic Hours
                <span class="badge" style="background:#e0f2fe;color:#0369a1;font-size:.7rem;">{{ count($rows) }}</span>
            </span>
            <button type="button" class="btn btn-sm px-3"
                    style="background:#0369a1;color:#fff;border:none;"
                    wire:click="openNestedItemModal('1', null, {{ json_encode($defaultItem) }})">
                <i class="mdi mdi-plus me-1"></i>Add Row
            </button>
        </div>

        <div class="p-3">
            {{-- Today's Label --}}
            <div class="mb-3">
                <label class="form-label small fw-semibold mb-1">Today's Label <small class="text-muted fw-normal">(shown on card)</small></label>
                <input type="text" class="form-control form-control-sm"
                       wire:model.blur="form.0"
                       placeholder="Today: Monday — Open till 8:30 PM">
            </div>

            {{-- ── Table ──────────────────────────────────────── --}}
            @if(count($rows) > 0)
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle mb-0" style="font-size:.83rem;">
                    <thead>
                        <tr style="background:linear-gradient(90deg,#f0f9ff,#e0f2fe);border-bottom:2px solid #bae6fd;">
                            <th class="px-2 py-2 text-muted fw-semibold" style="width:28px;font-size:.68rem;">#</th>
                            <th class="py-2 text-muted fw-semibold" style="font-size:.68rem;">Day</th>
                            <th class="py-2 text-muted fw-semibold" style="font-size:.68rem;">Session</th>
                            <th class="py-2 text-muted fw-semibold" style="font-size:.68rem;">Time</th>
                            <th class="py-2 text-muted fw-semibold" style="font-size:.68rem;">Style</th>
                            <th style="width:72px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $ri => $row)
                        @php
                            $rc    = $row['rowClass'] ?? '';
                            $rcCol = $rowClassColors[$rc] ?? '#64748b';
                            $rcBg  = $rowClassBg[$rc] ?? '#f1f5f9';
                        @endphp
                        <tr wire:key="hours-row-{{ $ri }}" class="border-bottom">
                            <td class="px-2 text-muted fw-semibold" style="font-size:.7rem;">{{ $ri + 1 }}</td>
                            <td class="py-1 fw-semibold">{{ $row['day'] ?: '—' }}</td>
                            <td class="py-1 text-muted" style="font-size:.78rem;">{{ $row['session'] ?? '—' }}</td>
                            <td class="py-1" style="font-size:.78rem;">{{ $row['time'] ?? '—' }}</td>
                            <td class="py-1">
                                <span class="badge rounded-pill px-2"
                                      style="background:{{ $rcBg }};color:{{ $rcCol }};border:1px solid {{ $rcCol }}44;font-size:.65rem;">
                                    {{ $rowClassLabels[$rc] ?? 'Default' }}
                                </span>
                            </td>
                            <td class="py-1 text-center" style="white-space:nowrap;">
                                <button type="button"
                                        class="btn btn-sm btn-outline-primary p-0 rounded-circle me-1"
                                        style="width:26px;height:26px;line-height:1;"
                                        wire:click="openNestedItemModal('1', {{ $ri }}, {{ json_encode($row) }})">
                                    <i class="mdi mdi-pencil-outline" style="font-size:12px;"></i>
                                </button>
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger p-0 rounded-circle"
                                        style="width:26px;height:26px;line-height:1;"
                                        x-on:click="showConfirmToast('Delete this row?', () => $wire.removeRowWithConfirm({{ $ri }}, '1'))">
                                    <i class="mdi mdi-delete" style="font-size:12px;"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="rounded-3 d-flex align-items-center justify-content-center py-4"
                 style="border:2px dashed #bae6fd;background:#f0f9ff;">
                <span class="text-muted small">
                    <i class="mdi mdi-clock-plus me-1" style="color:#93c5fd;"></i>
                    No hours yet — click <strong>Add Row</strong>.
                </span>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- ADD / EDIT ROW MODAL                                           --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="ss-item-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-3"
                 style="background:linear-gradient(90deg,#f0f9ff,#e0f2fe);border-bottom:2px solid #bae6fd;">
                <h5 class="modal-title fw-semibold d-flex align-items-center gap-2" style="color:#0369a1;">
                    <i class="mdi mdi-clock-outline"></i>
                    {{ $editingIndex !== null ? 'Edit Hours Row' : 'Add Hours Row' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-semibold mb-1">Day / Days</label>
                        <input type="text" class="form-control"
                               wire:model="editingItem.day"
                               placeholder="Monday – Friday">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small fw-semibold mb-1">Session</label>
                        <select class="form-select" wire:model="editingItem.session">
                            <option value="">—</option>
                            <option>Morning</option>
                            <option>Afternoon</option>
                            <option>Evening</option>
                            <option>Night</option>
                            <option>Emergency</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small fw-semibold mb-1">Time</label>
                        <input type="text" class="form-control"
                               wire:model="editingItem.time"
                               placeholder="9:00 – 11:30 AM or Closed">
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold mb-1">Row Style</label>
                        <select class="form-select" wire:model="editingItem.rowClass">
                            <option value="">Default</option>
                            <option value="open-row">Open</option>
                            <option value="closed">Closed</option>
                            <option value="emergency">Emergency</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-sm px-4"
                        style="background:#0369a1;color:#fff;border:none;"
                        wire:click="saveNestedItemModal('1')"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="saveNestedItemModal">
                        <i class="mdi mdi-content-save me-1"></i>Save
                    </span>
                    <span wire:loading wire:target="saveNestedItemModal">
                        <i class="mdi mdi-loading mdi-spin me-1"></i>Saving…
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
