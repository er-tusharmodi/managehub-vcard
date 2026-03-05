{{--
 | _shared/payments.blade.php
 | Inline editor for payment method lists across all templates.
 | Key shape varies by template:
 |  doctor / mens-salon / minimart:  {icon, stroke, name, detail}
 |  electronics:                     {name, detail, badge, icon, stroke}
 |  restaurant:                      {icon, name, sub, stroke}
 |  sweetshop:                       {type, name, detail}
--}}
@php
    $firstRow   = is_array($form) && !empty($form) ? reset($form) : [];
    $hasIcon    = array_key_exists('icon',   $firstRow);
    $hasType    = array_key_exists('type',   $firstRow);
    $hasStroke  = array_key_exists('stroke', $firstRow);
    $hasBadge   = array_key_exists('badge',  $firstRow);
    $detailKey  = array_key_exists('detail', $firstRow) ? 'detail'
                : (array_key_exists('sub', $firstRow) ? 'sub' : null);
    $iconKey    = $hasIcon ? 'icon' : ($hasType ? 'type' : null);
    $payCols    = array_keys($firstRow ?: ['icon'=>'','stroke'=>'','name'=>'','detail'=>'']);
    $payIcons   = ['cash','card','upi','wallet','cheque','insurance','shield','bank','neft','rtgs','netbanking','online'];
@endphp

<div class="col-12 mb-2">
    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom mb-2">
        <span class="fw-semibold text-uppercase text-muted" style="font-size:.7rem;letter-spacing:.07em;">
            <i class="mdi mdi-credit-card-outline me-1"></i>Payment Methods
            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ is_array($form) ? count($form) : 0 }}</span>
        </span>
        <button type="button" class="btn btn-sm btn-primary px-3"
                wire:click="addRowAndSave('', {{ json_encode($payCols) }})">
            <i class="mdi mdi-plus me-1"></i>Add Method
        </button>
    </div>
</div>

@if(is_array($form) && !empty($form))
    @foreach($form as $pi => $row)
    <div class="col-12 mb-2" wire:key="pay-row-{{ $pi }}">
        <div class="border rounded-3 p-3" style="background:#f8fafc;">
            <div class="row g-2 align-items-end">

                {{-- icon / type key --}}
                @if($iconKey)
                <div class="col-6 col-sm-2">
                    <label class="form-label small fw-semibold mb-1">{{ $hasIcon ? 'Icon Key' : 'Type Key' }}</label>
                    <input type="text"
                           class="form-control form-control-sm"
                           wire:model="form.{{ $pi }}.{{ $iconKey }}"
                           placeholder="upi / cash / card"
                           list="pay-icons-dl-{{ $pi }}">
                    <datalist id="pay-icons-dl-{{ $pi }}">
                        @foreach($payIcons as $ic)<option value="{{ $ic }}">@endforeach
                    </datalist>
                </div>
                @endif

                {{-- stroke --}}
                @if($hasStroke)
                <div class="col-6 col-sm-2">
                    <label class="form-label small fw-semibold mb-1">Colour</label>
                    <div class="input-group input-group-sm">
                        <input type="color"
                               class="form-control form-control-sm form-control-color p-0"
                               style="max-width:38px;"
                               wire:model="form.{{ $pi }}.stroke">
                        <input type="text"
                               class="form-control form-control-sm"
                               wire:model="form.{{ $pi }}.stroke"
                               placeholder="#6366f1">
                    </div>
                </div>
                @endif

                {{-- name --}}
                <div class="col-sm-{{ $hasBadge ? '3' : '4' }}">
                    <label class="form-label small fw-semibold mb-1">Name</label>
                    <input type="text"
                           class="form-control form-control-sm"
                           wire:model="form.{{ $pi }}.name"
                           placeholder="UPI / Cash / Card">
                </div>

                {{-- detail / sub --}}
                @if($detailKey)
                <div class="col-sm-{{ $hasBadge ? '3' : '4' }}">
                    <label class="form-label small fw-semibold mb-1">{{ $detailKey === 'sub' ? 'Sub-label' : 'Detail' }}</label>
                    <input type="text"
                           class="form-control form-control-sm"
                           wire:model="form.{{ $pi }}.{{ $detailKey }}"
                           placeholder="Scan &amp; Pay / Accepted here">
                </div>
                @endif

                {{-- badge (electronics only) --}}
                @if($hasBadge)
                <div class="col-sm-2">
                    <label class="form-label small fw-semibold mb-1">Badge</label>
                    <input type="text"
                           class="form-control form-control-sm"
                           wire:model="form.{{ $pi }}.badge"
                           placeholder="Popular">
                </div>
                @endif

                {{-- Delete --}}
                <div class="col-auto d-flex align-items-end pb-1">
                    <button type="button"
                            class="btn btn-sm btn-outline-danger p-0 rounded-circle"
                            style="width:28px;height:28px;"
                            wire:click="removeRow('',{{ $pi }})"
                            wire:confirm="Delete this payment method?">
                        <i class="mdi mdi-delete" style="font-size:12px;"></i>
                    </button>
                </div>

            </div>
        </div>
    </div>
    @endforeach
@else
    <div class="col-12">
        <div class="text-center py-4 rounded-3"
             style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);border:2px dashed #cbd5e1;">
            <i class="mdi mdi-credit-card-plus-outline fs-1 text-muted mb-2 d-block"></i>
            <p class="fw-semibold text-muted mb-2 small">No payment methods yet</p>
            <button type="button" class="btn btn-sm btn-primary"
                    wire:click="addRowAndSave('', {{ json_encode($payCols) }})">
                <i class="mdi mdi-plus me-1"></i>Add First Method
            </button>
        </div>
    </div>
@endif
