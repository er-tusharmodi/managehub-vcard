{{--
 | _shared/payments.blade.php
 | Inline editor for payment method lists across all templates.
 | Used for sections: payments, paymentMethods
 |
 | Key shape varies by template:
 |  doctor / mens-salon / minimart:  {icon, stroke, name, detail}
 |  electronics:                     {name, detail, badge, icon, stroke}
 |  restaurant:                      {icon, name, sub, stroke}       (sub = detail)
 |  sweetshop:                       {type, name, detail}            (type = icon key, no stroke)
--}}

@php
    $firstRow   = is_array($form) && !empty($form) ? reset($form) : [];
    $hasIcon    = array_key_exists('icon',   $firstRow);
    $hasType    = array_key_exists('type',   $firstRow);
    $hasStroke  = array_key_exists('stroke', $firstRow);
    $hasBadge   = array_key_exists('badge',  $firstRow);
    $detailKey  = array_key_exists('detail', $firstRow) ? 'detail'
                : (array_key_exists('sub',   $firstRow) ? 'sub' : null);
    $iconKey    = $hasIcon ? 'icon' : ($hasType ? 'type' : null);
@endphp

<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-credit-card-outline me-1"></i>Payment Methods
    </h6>
    <small class="text-muted">Each row = one accepted payment method.</small>
</div>

@if(is_array($form) && !empty($form))
    @foreach($form as $pi => $row)
    <div class="col-12 mb-2" wire:key="pay-row-{{ $pi }}">
        <div class="border rounded-3 p-3 bg-light">
            <div class="row g-2 align-items-end">

                {{-- icon / type key --}}
                @if($iconKey)
                <div class="col-6 col-sm-2">
                    <label class="form-label small fw-semibold mb-1">
                        {{ $hasIcon ? 'Icon Name' : 'Type Key' }}
                    </label>
                    <input type="text"
                           class="form-control form-control-sm"
                           wire:model="form.{{ $pi }}.{{ $iconKey }}"
                           placeholder="{{ $hasIcon ? 'upi / cash / card' : 'upi' }}">
                </div>
                @endif

                {{-- stroke --}}
                @if($hasStroke)
                <div class="col-6 col-sm-2">
                    <label class="form-label small fw-semibold mb-1">Stroke / Variant</label>
                    <input type="text"
                           class="form-control form-control-sm"
                           wire:model="form.{{ $pi }}.stroke"
                           placeholder="#6366f1">
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
                    <label class="form-label small fw-semibold mb-1">
                        {{ $detailKey === 'sub' ? 'Sub-label' : 'Detail' }}
                    </label>
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

            </div>
        </div>
    </div>
    @endforeach
@else
    <div class="col-12">
        <p class="text-muted small"><i class="mdi mdi-information-outline me-1"></i>No payment methods found.</p>
    </div>
@endif
