{{-- doctor-clinic-template/appointment.blade.php --}}
{{-- form[0]=defaultSlot string, form[1]=[{slot,session,time,availability,full}] --}}
@php
    $slots = is_array($form[1] ?? null) ? $form[1] : [];
    $defaultItem = ['slot'=>'','session'=>'Morning','time'=>'','availability'=>'','full'=>false];
@endphp

{{-- ── Section Header ──────────────────────────────────────────── --}}
<div class="col-12 mb-2">
    <div class="border rounded-3 overflow-hidden shadow-sm">
        <div class="d-flex align-items-center justify-content-between px-3 py-2"
             style="background:linear-gradient(90deg,#f0fdf4,#dcfce7);">
            <span class="fw-semibold d-flex align-items-center gap-2" style="font-size:.85rem;color:#166534;">
                <i class="mdi mdi-calendar-clock"></i>
                Appointment Slots
                <span class="badge" style="background:#dcfce7;color:#166534;font-size:.7rem;">{{ count($slots) }}</span>
            </span>
            <button type="button" class="btn btn-sm px-3"
                    style="background:#166534;color:#fff;border:none;"
                    wire:click="openNestedItemModal('1', null, {{ json_encode($defaultItem) }})">
                <i class="mdi mdi-plus me-1"></i>Add Slot
            </button>
        </div>

        <div class="p-3">
            {{-- Default Slot field --}}
            <div class="mb-3">
                <label class="form-label small fw-semibold mb-1">
                    <i class="mdi mdi-check-circle-outline me-1 text-muted"></i>Default Selected Slot
                </label>
                <input type="text" class="form-control form-control-sm"
                       wire:model.blur="form.0" placeholder="Morning: 9–11 AM">
                <small class="text-muted">Auto-selected when the page loads.</small>
            </div>

            {{-- ── Table ────────────────────────────────────────── --}}
            @if(count($slots) > 0)
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle mb-0" style="font-size:.83rem;">
                    <thead>
                        <tr style="background:linear-gradient(90deg,#f0fdf4,#dcfce7);border-bottom:2px solid #86efac;">
                            <th class="px-2 py-2 text-muted fw-semibold" style="width:28px;font-size:.68rem;">#</th>
                            <th class="py-2 text-muted fw-semibold" style="font-size:.68rem;">Slot Name</th>
                            <th class="py-2 text-muted fw-semibold" style="font-size:.68rem;">Session</th>
                            <th class="py-2 text-muted fw-semibold" style="font-size:.68rem;">Time</th>
                            <th class="py-2 text-muted fw-semibold" style="font-size:.68rem;">Availability</th>
                            <th class="py-2 text-muted fw-semibold" style="width:54px;font-size:.68rem;">Full?</th>
                            <th style="width:72px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($slots as $si => $slot)
                        @php $isFull = !empty($slot['full']); @endphp
                        <tr wire:key="appt-slot-{{ $si }}" class="border-bottom">
                            <td class="px-2 text-muted fw-semibold" style="font-size:.7rem;">{{ $si + 1 }}</td>
                            <td class="py-1 fw-semibold">{{ $slot['slot'] ?: '—' }}</td>
                            <td class="py-1 text-muted" style="font-size:.78rem;">{{ $slot['session'] ?? '—' }}</td>
                            <td class="py-1" style="font-size:.78rem;">{{ $slot['time'] ?? '—' }}</td>
                            <td class="py-1 text-muted" style="font-size:.78rem;">{{ $slot['availability'] ?? '—' }}</td>
                            <td class="py-1 text-center">
                                @if($isFull)
                                    <span class="badge" style="background:#fee2e2;color:#991b1b;font-size:.65rem;">FULL</span>
                                @else
                                    <span class="badge" style="background:#dcfce7;color:#166534;font-size:.65rem;">Open</span>
                                @endif
                            </td>
                            <td class="py-1 text-center" style="white-space:nowrap;">
                                <button type="button"
                                        class="btn btn-sm btn-outline-primary p-0 rounded-circle me-1"
                                        style="width:26px;height:26px;line-height:1;"
                                        wire:click="openNestedItemModal('1', {{ $si }}, {{ json_encode($slot) }})">
                                    <i class="mdi mdi-pencil-outline" style="font-size:12px;"></i>
                                </button>
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger p-0 rounded-circle"
                                        style="width:26px;height:26px;line-height:1;"
                                        x-on:click="showConfirmToast('Delete this slot?', () => $wire.removeRowWithConfirm({{ $si }}, '1'))">
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
                 style="border:2px dashed #86efac;background:#f0fdf4;">
                <span class="text-muted small">
                    <i class="mdi mdi-calendar-plus me-1" style="color:#86efac;"></i>
                    No slots yet — click <strong>Add Slot</strong>.
                </span>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════ --}}
{{-- ADD / EDIT SLOT MODAL                                          --}}
{{-- ══════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="ss-item-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-3"
                 style="background:linear-gradient(90deg,#f0fdf4,#dcfce7);border-bottom:2px solid #86efac;">
                <h5 class="modal-title fw-semibold d-flex align-items-center gap-2" style="color:#166534;">
                    <i class="mdi mdi-calendar-clock"></i>
                    {{ $editingIndex !== null ? 'Edit Slot' : 'Add Slot' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label small fw-semibold mb-1">Slot Name</label>
                        <input type="text" class="form-control"
                               wire:model="editingItem.slot"
                               placeholder="Morning: 9–11 AM">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small fw-semibold mb-1">Session</label>
                        <select class="form-select" wire:model="editingItem.session">
                            <option value="">— Select —</option>
                            <option>Morning</option>
                            <option>Afternoon</option>
                            <option>Noon</option>
                            <option>Evening</option>
                            <option>Night</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label small fw-semibold mb-1">Time Display</label>
                        <input type="text" class="form-control"
                               wire:model="editingItem.time"
                               placeholder="9:00 – 11:00 AM">
                    </div>
                    <div class="col-sm-8">
                        <label class="form-label small fw-semibold mb-1">Availability</label>
                        <input type="text" class="form-control"
                               wire:model="editingItem.availability"
                               placeholder="4 slots left">
                    </div>
                    <div class="col-sm-4 d-flex align-items-end pb-1">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   id="modal-slot-full"
                                   wire:model="editingItem.full">
                            <label class="form-check-label small fw-semibold" for="modal-slot-full">Mark Full</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-sm px-4"
                        style="background:#166534;color:#fff;border:none;"
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


