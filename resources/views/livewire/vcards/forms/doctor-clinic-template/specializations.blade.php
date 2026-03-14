{{-- doctor-clinic-template/specializations.blade.php --}}
{{-- Items: [{name, icon}] — tone auto-assigned by index --}}
@php
    require_once resource_path('views/vcards/icons/doctor-clinic-template.php');
    $specs = is_array($form) ? array_values($form) : [];
    $palette = ['#2563eb','#0d9488','#16a34a','#7c3aed','#dc2626','#d97706'];
    // These keys map to chip_* icons in the icons file
    $iconOptions = [
        'pulse'       => 'Pulse',
        'heart'       => 'Heart',
        'respiratory' => 'Lungs',
        'home'        => 'Home Visit',
        'search'      => 'Diagnosis',
        'preventive'  => 'Preventive',
        'info'        => 'Info',
    ];
@endphp

{{-- ── Header ──────────────────────────────────────────────────── --}}
<div class="col-12 mb-2">
    <div class="border rounded-3 overflow-hidden shadow-sm">
        <div class="d-flex align-items-center justify-content-between px-3 py-2"
             style="background:linear-gradient(90deg,#f0f9ff,#e0f2fe);">
            <span class="fw-semibold d-flex align-items-center gap-2" style="font-size:.85rem;color:#0369a1;">
                <i class="mdi mdi-medical-bag"></i>
                Specializations
                <span class="badge" style="background:#e0f2fe;color:#0369a1;font-size:.7rem;">{{ count($specs) }}</span>
            </span>
            <button type="button" class="btn btn-sm px-3"
                    style="background:#0369a1;color:#fff;border:none;"
                    wire:click="openItemModal(null, {{ json_encode(['name' => '', 'icon' => 'pulse']) }})">
                <i class="mdi mdi-plus me-1"></i>Add
            </button>
        </div>

        <div class="p-3">
            @if(count($specs) > 0)
                <div class="d-flex flex-wrap gap-2">
                    @foreach($specs as $si => $spec)
                    @php
                        $col  = $palette[$si % count($palette)];
                        $icon = $spec['icon'] ?? 'pulse';
                        $name = $spec['name'] ?? 'Specialization';
                    @endphp
                    <div class="d-inline-flex align-items-center gap-1 rounded-pill px-2 py-1 border"
                         wire:key="dr-spec-{{ $si }}"
                         style="background:{{ $col }}12;border-color:{{ $col }}44 !important;">
                        <span class="rounded-circle flex-shrink-0"
                              style="width:8px;height:8px;background:{{ $col }};"></span>
                        <span class="fw-semibold text-truncate"
                              style="font-size:.8rem;color:{{ $col }};max-width:130px;">{{ $name ?: '(empty)' }}</span>
                        <span style="display:inline-flex;width:16px;height:16px;color:{{ $col }};opacity:.8;">{!! getIcon('chip_' . $icon) !!}</span>
                        {{-- Edit --}}
                        <button type="button"
                                class="btn btn-sm p-0 ms-1 flex-shrink-0"
                                style="width:22px;height:22px;line-height:1;color:{{ $col }};border:1px solid {{ $col }}44;border-radius:50%;background:{{ $col }}18;"
                                wire:click="openItemModal({{ $si }}, {{ json_encode(['name' => $name, 'icon' => $icon]) }})">
                            <i class="mdi mdi-pencil-outline" style="font-size:11px;"></i>
                        </button>
                        {{-- Delete --}}
                        <button type="button"
                                class="btn btn-sm p-0 flex-shrink-0"
                                style="width:22px;height:22px;line-height:1;color:#dc2626;border:1px solid #fca5a544;border-radius:50%;background:#fef2f2;"
                                x-on:click="showConfirmToast('Remove this specialization?', () => $wire.removeRowWithConfirm({{ $si }}, ''))">
                            <i class="mdi mdi-close" style="font-size:11px;"></i>
                        </button>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-3 d-flex align-items-center justify-content-center py-4"
                     style="border:2px dashed #bfdbfe;background:#f0f9ff;">
                    <span class="text-muted small">
                        <i class="mdi mdi-medical-bag me-1" style="color:#93c5fd;"></i>
                        No specializations yet — click <strong>Add</strong>.
                    </span>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- ADD / EDIT MODAL --}}
<div class="modal fade" id="ss-item-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-3"
                 style="background:linear-gradient(90deg,#f0f9ff,#e0f2fe);border-bottom:2px solid #bae6fd;">
                <h5 class="modal-title fw-semibold d-flex align-items-center gap-2" style="color:#0369a1;">
                    <i class="mdi mdi-medical-bag"></i>
                    {{ $editingIndex !== null ? 'Edit Specialization' : 'Add Specialization' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    {{-- Name --}}
                    <div class="col-12">
                        <label class="form-label small fw-semibold mb-1">Specialization Name</label>
                        <input type="text" class="form-control"
                               wire:model="editingItem.name"
                               placeholder="e.g. Cardiology, Pediatrics…">
                    </div>
                    {{-- Icon picker — Alpine tracks selection locally; wire:model deferred to Save --}}
                    <div class="col-12">
                        <label class="form-label small fw-semibold mb-2">Icon</label>
                        <div class="d-flex flex-wrap gap-2"
                             x-data="{ selectedIcon: '{{ $editingItem['icon'] ?? 'pulse' }}' }">
                            @foreach($iconOptions as $iKey => $iLabel)
                            <label class="d-flex align-items-center gap-2 px-3 py-2 rounded-3 border"
                                   style="cursor:pointer;font-size:.78rem;user-select:none;transition:all .13s;"
                                   :style="selectedIcon === '{{ $iKey }}'
                                       ? 'background:#e0f2fe;border-color:#38bdf8;font-weight:600;color:#0369a1;'
                                       : 'background:#fff;border-color:#e2e8f0;color:inherit;'">
                                <input type="radio" name="spec_icon_pick"
                                       value="{{ $iKey }}"
                                       wire:model="editingItem.icon"
                                       x-on:change="selectedIcon = '{{ $iKey }}'"
                                       class="d-none">
                                <span style="display:inline-flex;width:18px;height:18px;flex-shrink:0;"
                                      :style="'color:' + (selectedIcon === '{{ $iKey }}' ? '#0369a1' : '#64748b')">
                                    {!! getIcon('chip_' . $iKey) !!}
                                </span>
                                <span>{{ $iLabel }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-outline-secondary btn-sm"
                        data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-sm px-4"
                        style="background:#0369a1;color:#fff;border:none;"
                        wire:click="saveItemModal()"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="saveItemModal">
                        <i class="mdi mdi-content-save me-1"></i>Save
                    </span>
                    <span wire:loading wire:target="saveItemModal">
                        <i class="mdi mdi-loading mdi-spin me-1"></i>Saving…
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
