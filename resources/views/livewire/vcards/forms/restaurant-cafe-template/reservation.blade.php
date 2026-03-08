{{-- restaurant-cafe-template/reservation.blade.php --}}
{{-- Editable: Time Slots, Guest Count Options, Occasion Options.
     Success Screen and Form Field Labels intentionally removed. --}}

<div class="col-12 mb-3">
    <h6 class="fw-semibold text-muted text-uppercase mb-1" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-table-chair me-1"></i>Table Reservation
    </h6>
    <small class="text-muted">Configure the time slots, guest counts and occasion options available to guests when booking a table.</small>
</div>

{{-- ── Time Slots ────────────────────────────────────────────────────────── --}}
<div class="col-12">
    <div class="card border shadow-sm overflow-hidden" style="border-radius:.75rem;">
        <div class="card-header d-flex align-items-center justify-content-between py-2 px-3"
             style="background:linear-gradient(90deg,#eff6ff,#dbeafe);border-bottom:1px solid #bfdbfe;">
            <span class="fw-semibold text-primary d-flex align-items-center gap-2" style="font-size:.85rem;">
                <i class="mdi mdi-clock-outline"></i>
                Available Time Slots
                <span class="badge bg-primary-subtle text-primary" style="font-size:.7rem;">
                    {{ count($form['times'] ?? []) }}
                </span>
            </span>
            <button type="button" class="btn btn-primary btn-sm px-3"
                    wire:click="addRowAndSave('times', ['label','value'])">
                <i class="mdi mdi-plus me-1"></i>Add Slot
            </button>
        </div>
        <div class="card-body p-2">
            @forelse(($form['times'] ?? []) as $ti => $t)
            <div class="d-flex align-items-center gap-2 mb-2" wire:key="rt-time-{{ $ti }}">
                <span class="badge bg-primary-subtle text-primary" style="min-width:28px;">{{ $ti + 1 }}</span>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.times.{{ $ti }}.label"
                       placeholder="e.g. 12:00 PM">
                <button type="button" class="btn btn-outline-danger btn-sm"
                        x-on:click="showConfirmToast('Remove this time slot?', () => $wire.removeRowWithConfirm({{ $ti }}, 'times'))">
                    <i class="mdi mdi-delete-outline" style="font-size:13px;"></i>
                </button>
            </div>
            @empty
            <p class="text-muted small text-center py-2 mb-0">No time slots yet. Add the first one above.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- ── Guest Count Options ───────────────────────────────────────────────── --}}
<div class="col-12 mt-3">
    <div class="card border shadow-sm overflow-hidden" style="border-radius:.75rem;">
        <div class="card-header d-flex align-items-center justify-content-between py-2 px-3"
             style="background:linear-gradient(90deg,#f0fdf4,#dcfce7);border-bottom:1px solid #bbf7d0;">
            <span class="fw-semibold text-success-emphasis d-flex align-items-center gap-2" style="font-size:.85rem;">
                <i class="mdi mdi-account-group-outline"></i>
                Guest Count Options
                <span class="badge bg-success-subtle text-success-emphasis" style="font-size:.7rem;">
                    {{ count($form['guests'] ?? []) }}
                </span>
            </span>
            <button type="button" class="btn btn-success btn-sm px-3"
                    wire:click="addRowAndSave('guests', ['label','value'])">
                <i class="mdi mdi-plus me-1"></i>Add Option
            </button>
        </div>
        <div class="card-body p-2">
            <div class="d-flex flex-wrap gap-2">
                @forelse(($form['guests'] ?? []) as $gi => $g)
                <div class="input-group input-group-sm" style="width:auto;max-width:160px;" wire:key="rt-guest-{{ $gi }}">
                    <span class="input-group-text bg-success-subtle text-success-emphasis border-success-subtle px-2">
                        <i class="mdi mdi-account"></i>
                    </span>
                    <input type="text" class="form-control border-success-subtle"
                           wire:model="form.guests.{{ $gi }}.label"
                           placeholder="4" style="min-width:50px;">
                    <button type="button" class="btn btn-outline-danger btn-sm"
                            x-on:click="showConfirmToast('Remove this option?', () => $wire.removeRowWithConfirm({{ $gi }}, 'guests'))">
                        <i class="mdi mdi-close" style="font-size:12px;"></i>
                    </button>
                </div>
                @empty
                <p class="text-muted small mb-0">No guest options yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ── Occasion Options ──────────────────────────────────────────────────── --}}
<div class="col-12 mt-3 mb-3">
    <div class="card border shadow-sm overflow-hidden" style="border-radius:.75rem;">
        <div class="card-header d-flex align-items-center justify-content-between py-2 px-3"
             style="background:linear-gradient(90deg,#fdf4ff,#fae8ff);border-bottom:1px solid #e9d5ff;">
            <span class="fw-semibold d-flex align-items-center gap-2" style="font-size:.85rem;color:#7e22ce;">
                <i class="mdi mdi-party-popper"></i>
                Occasion Options
                <span class="badge" style="background:#f3e8ff;color:#7e22ce;font-size:.7rem;">
                    {{ count($form['occasions'] ?? []) }}
                </span>
            </span>
            <button type="button" class="btn btn-sm px-3" style="background:#7e22ce;color:#fff;"
                    wire:click="addRowAndSave('occasions', ['label','value'])">
                <i class="mdi mdi-plus me-1"></i>Add Occasion
            </button>
        </div>
        <div class="card-body p-2">
            <div class="d-flex flex-wrap gap-2">
                @forelse(($form['occasions'] ?? []) as $oi => $occ)
                <div class="input-group input-group-sm" style="width:auto;max-width:200px;" wire:key="rt-occ-{{ $oi }}">
                    <span class="input-group-text px-2"
                          style="background:#f3e8ff;border-color:#e9d5ff;color:#7e22ce;">
                        <i class="mdi mdi-party-popper" style="font-size:13px;"></i>
                    </span>
                    <input type="text" class="form-control" style="border-color:#e9d5ff;"
                           wire:model="form.occasions.{{ $oi }}.label"
                           placeholder="Birthday">
                    <button type="button" class="btn btn-outline-danger btn-sm"
                            x-on:click="showConfirmToast('Remove this occasion?', () => $wire.removeRowWithConfirm({{ $oi }}, 'occasions'))">
                        <i class="mdi mdi-close" style="font-size:12px;"></i>
                    </button>
                </div>
                @empty
                <p class="text-muted small mb-0">No occasions yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
