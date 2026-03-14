{{-- sweetshop-template/paymentMethods.blade.php — [{type, name, detail}] --}}
@php
    $payIconDefs = [
        'upi'  => ['label' => 'UPI',          'color' => '#2e7d32',
                   'svg' => '<svg viewBox="0 0 24 24" stroke-width="1.8" fill="none" stroke="currentColor" style="width:18px;height:18px;"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>'],
        'bank' => ['label' => 'Bank Transfer', 'color' => '#1565c0',
                   'svg' => '<svg viewBox="0 0 24 24" stroke-width="1.8" fill="none" stroke="currentColor" style="width:18px;height:18px;"><line x1="3" y1="22" x2="21" y2="22"/><line x1="6" y1="18" x2="6" y2="11"/><line x1="10" y1="18" x2="10" y2="11"/><line x1="14" y1="18" x2="14" y2="11"/><line x1="18" y1="18" x2="18" y2="11"/><polygon points="12 2 20 7 4 7"/></svg>'],
        'cash' => ['label' => 'Cash',          'color' => '#37474f',
                   'svg' => '<svg viewBox="0 0 24 24" stroke-width="1.8" fill="none" stroke="currentColor" style="width:18px;height:18px;"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>'],
    ];
@endphp

{{-- ── Section Header ──────────────────────────────────────────── --}}
<div class="col-12 mb-2">
    <div class="d-flex align-items-center justify-content-between">
        <h6 class="fw-semibold text-muted text-uppercase mb-0"
            style="font-size:.72rem;letter-spacing:.07em;">
            <i class="mdi mdi-cash-multiple me-1"></i>Payment Methods
            <span class="badge bg-success-subtle text-success-emphasis ms-1">{{ count($form ?? []) }}</span>
        </h6>
        <button type="button" class="btn btn-success btn-sm px-3"
                wire:click="openPaymentModal()">
            <i class="mdi mdi-plus me-1"></i>Add Method
        </button>
    </div>
</div>

{{-- ── Table ────────────────────────────────────────────────────── --}}
<div class="col-12">
    <div class="card border shadow-sm overflow-hidden" style="border-radius:.75rem;">
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0" style="font-size:.83rem;">
                <thead>
                    <tr style="background:linear-gradient(90deg,#fdf4ff,#fae8ff);border-bottom:2px solid #e879f9;">
                        <th class="px-2 py-2 text-muted fw-semibold" style="width:28px;font-size:.68rem;">#</th>
                        <th class="py-2 text-muted fw-semibold" style="width:44px;font-size:.68rem;">Icon</th>
                        <th class="py-2 text-muted fw-semibold" style="min-width:90px;font-size:.68rem;">Type</th>
                        <th class="py-2 text-muted fw-semibold" style="min-width:110px;font-size:.68rem;">Name</th>
                        <th class="py-2 text-muted fw-semibold" style="font-size:.68rem;">Detail</th>
                        <th style="width:72px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($form ?? []) as $pi => $row)
                    @php
                        $curType   = $row['type']   ?? 'cash';
                        $curName   = $row['name']   ?? '';
                        $curDetail = $row['detail'] ?? '';
                        $iconMeta  = $payIconDefs[$curType] ?? $payIconDefs['cash'];
                    @endphp
                    <tr wire:key="ss-pay-row-{{ $pi }}" class="border-bottom">
                        <td class="px-2 text-muted fw-semibold" style="font-size:.7rem;">{{ $pi + 1 }}</td>
                        <td class="py-1 text-center">
                            <span style="display:inline-flex;color:{{ $iconMeta['color'] }};">
                                {!! $iconMeta['svg'] !!}
                            </span>
                        </td>
                        <td class="py-1">
                            <span class="badge rounded-pill"
                                  style="background:{{ $iconMeta['color'] }}1a;color:{{ $iconMeta['color'] }};border:1px solid {{ $iconMeta['color'] }}44;font-size:.65rem;">
                                {{ $iconMeta['label'] }}
                            </span>
                        </td>
                        <td class="py-1 fw-semibold">{{ $curName ?: '—' }}</td>
                        <td class="py-1 text-muted" style="font-size:.78rem;">{{ $curDetail ?: '—' }}</td>
                        <td class="py-1 text-center" style="white-space:nowrap;">
                            <button type="button"
                                    class="btn btn-sm btn-outline-primary p-0 rounded-circle me-1"
                                    style="width:26px;height:26px;line-height:1;"
                                    wire:click="openPaymentModal({{ $pi }})">
                                <i class="mdi mdi-pencil-outline" style="font-size:12px;"></i>
                            </button>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger p-0 rounded-circle"
                                    style="width:26px;height:26px;line-height:1;"
                                    x-on:click="showConfirmToast('Remove this payment method?', () => $wire.removeRowWithConfirm({{ $pi }}, ''), '{{ addslashes($curName) }}')">
                                <i class="mdi mdi-delete" style="font-size:12px;"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-4 text-center text-muted">
                            <i class="mdi mdi-cash-plus d-block" style="font-size:2rem;opacity:.35;"></i>
                            <small>No payment methods yet. Click <strong>Add Method</strong> to create one.</small>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- PAYMENT MODAL (Add / Edit)                                             --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
        <div class="modal-content border-0 shadow">

            {{-- Header --}}
            <div class="modal-header py-3"
                 style="background:linear-gradient(90deg,#fdf4ff,#fae8ff);border-bottom:2px solid #e879f9;">
                <h5 class="modal-title fw-semibold d-flex align-items-center gap-2" id="paymentModalLabel">
                    <i class="mdi mdi-cash-multiple" style="color:#86198f;"></i>
                    <span style="color:#86198f;">
                        {{ $editingIndex !== null ? 'Edit Payment Method' : 'Add Payment Method' }}
                    </span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body p-4"
                 x-data="{ type: @entangle('editingItem.type').live }">
                <div class="row g-3">

                    {{-- Type picker --}}
                    <div class="col-12">
                        <label class="form-label small fw-semibold mb-2 d-block text-muted"
                               style="font-size:.68rem;letter-spacing:.05em;text-transform:uppercase;">
                            Payment Type
                        </label>
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach($payIconDefs as $typeOpt => $typeMeta)
                            <label class="d-flex flex-column align-items-center justify-content-center gap-1 rounded-3 border"
                                   style="min-width:80px;padding:.6rem .4rem;cursor:pointer;transition:all .15s;user-select:none;"
                                   x-bind:style="type === '{{ $typeOpt }}'
                                       ? 'background:#fdf4ff;border-color:#e879f9;box-shadow:0 0 0 2px #f5d0fe;'
                                       : 'background:#fff;border-color:#e2e8f0;'"
                                   @click="type = '{{ $typeOpt }}'">
                                <input type="radio" name="pm_type_modal" value="{{ $typeOpt }}"
                                       wire:model="editingItem.type" class="d-none">
                                <span style="display:flex;color:{{ $typeMeta['color'] }};">
                                    {!! $typeMeta['svg'] !!}
                                </span>
                                <span style="font-size:.63rem;font-weight:600;color:#64748b;line-height:1.2;margin-top:2px;white-space:nowrap;">
                                    {{ $typeMeta['label'] }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Name --}}
                    <div class="col-sm-6">
                        <label class="form-label small fw-semibold mb-1">Name</label>
                        <input type="text" class="form-control"
                               wire:model.blur="editingItem.name"
                               placeholder="UPI / Bank Transfer / Cash">
                    </div>

                    {{-- Detail --}}
                    <div class="col-sm-6">
                        <label class="form-label small fw-semibold mb-1">Detail</label>
                        <input type="text" class="form-control"
                               wire:model.blur="editingItem.detail"
                               placeholder="UPI ID: shop@okaxis">
                    </div>

                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer border-top py-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-sm px-4"
                        style="background:#86198f;color:#fff;border:none;"
                        onclick="window.__paymentSaveItem()">
                    <i class="mdi mdi-content-save-outline me-1"></i>Save Method
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    (function () {
        if (window.__paymentModalListenerRegistered) { return; }
        window.__paymentModalListenerRegistered = true;

        function cleanBackdrops() {
            document.querySelectorAll('.modal-backdrop').forEach(function (el) { el.remove(); });
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');
        }

        function hideInstant(id) {
            var el = document.getElementById(id);
            if (el) {
                el.classList.remove('show');
                el.style.display = 'none';
                el.setAttribute('aria-hidden', 'true');
                el.removeAttribute('aria-modal');
                el.removeAttribute('role');
                var inst = bootstrap.Modal.getInstance(el);
                if (inst) { inst.dispose(); }
            }
            cleanBackdrops();
        }

        window.__paymentSaveItem = function () {
            var comp = window.__paymentWireComp;
            if (!comp) { console.error('Payment Livewire component not found'); return; }
            hideInstant('paymentModal');
            comp.$call('savePaymentModal');
        };

        document.addEventListener('open-payment-modal', function (e) {
            var wireId = e.detail && e.detail.wireId ? e.detail.wireId : null;
            window.__paymentWireComp = wireId ? Livewire.find(wireId) : null;
            cleanBackdrops();
            bootstrap.Modal.getOrCreateInstance(document.getElementById('paymentModal')).show();
        });

        document.addEventListener('hide-payment-modal', function () { hideInstant('paymentModal'); });
    })();
</script>