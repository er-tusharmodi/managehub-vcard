{{-- doctor-clinic-template/payments.blade.php — [{name, detail, icon, stroke}] --}}
@php
    $payIconDefs = [
        'card'   => ['label' => 'Cards',  'color' => '#1565c0',
                     'svg' => '<svg viewBox="0 0 24 24" stroke-width="1.8" fill="none" stroke="currentColor" style="width:18px;height:18px;"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>'],
        'upi'    => ['label' => 'UPI',    'color' => '#2e7d32',
                     'svg' => '<svg viewBox="0 0 24 24" stroke-width="1.8" fill="none" stroke="currentColor" style="width:18px;height:18px;"><circle cx="12" cy="12" r="10"/><path d="M8 12h8M12 8v8"/></svg>'],
        'cash'   => ['label' => 'Cash',   'color' => '#37474f',
                     'svg' => '<svg viewBox="0 0 24 24" stroke-width="1.8" fill="none" stroke="currentColor" style="width:18px;height:18px;"><path d="M17 9V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><rect x="9" y="11" width="12" height="10" rx="2"/><circle cx="15" cy="16" r="1"/></svg>'],
        'shield' => ['label' => 'Secure', 'color' => '#0d9488',
                     'svg' => '<svg viewBox="0 0 24 24" stroke-width="1.8" fill="none" stroke="currentColor" style="width:18px;height:18px;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>'],
    ];
@endphp

{{-- ── Section Header ──────────────────────────────────────────── --}}
<div class="col-12 mb-2">
    <div class="d-flex align-items-center justify-content-between">
        <h6 class="fw-semibold text-muted text-uppercase mb-0"
            style="font-size:.72rem;letter-spacing:.07em;">
            <i class="mdi mdi-credit-card-outline me-1"></i>Payment Methods
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
                    <tr style="background:linear-gradient(90deg,#f0fdf4,#dcfce7);border-bottom:2px solid #86efac;">
                        <th class="px-2 py-2 text-muted fw-semibold" style="width:28px;font-size:.68rem;">#</th>
                        <th class="py-2 text-muted fw-semibold" style="width:44px;font-size:.68rem;">Icon</th>
                        <th class="py-2 text-muted fw-semibold" style="min-width:120px;font-size:.68rem;">Name</th>
                        <th class="py-2 text-muted fw-semibold" style="font-size:.68rem;">Detail</th>
                        <th class="py-2 text-muted fw-semibold" style="width:54px;font-size:.68rem;">Colour</th>
                        <th style="width:72px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(($form ?? []) as $pi => $row)
                    @php
                        $curIcon   = $row['icon']   ?? 'card';
                        $curStroke = $row['stroke'] ?? ($payIconDefs[$curIcon]['color'] ?? '#0d9488');
                        $curName   = $row['name']   ?? '';
                        $curDetail = $row['detail'] ?? '';
                    @endphp
                    <tr wire:key="dc-pay-row-{{ $pi }}" class="border-bottom">
                        <td class="px-2 text-muted fw-semibold" style="font-size:.7rem;">{{ $pi + 1 }}</td>
                        <td class="py-1 text-center">
                            <span style="display:inline-flex;color:{{ $curStroke }};">
                                {!! $payIconDefs[$curIcon]['svg'] ?? $payIconDefs['card']['svg'] !!}
                            </span>
                        </td>
                        <td class="py-1 fw-semibold">{{ $curName ?: '—' }}</td>
                        <td class="py-1 text-muted" style="font-size:.78rem;">{{ $curDetail ?: '—' }}</td>
                        <td class="py-1 text-center">
                            <span class="d-inline-block rounded-circle border"
                                  style="width:18px;height:18px;background:{{ $curStroke }};vertical-align:middle;"></span>
                        </td>
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
                            <i class="mdi mdi-credit-card-plus-outline d-block" style="font-size:2rem;opacity:.35;"></i>
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
                 style="background:linear-gradient(90deg,#f0fdf4,#dcfce7);border-bottom:2px solid #86efac;">
                <h5 class="modal-title fw-semibold d-flex align-items-center gap-2" id="paymentModalLabel">
                    <i class="mdi mdi-credit-card-outline" style="color:#166534;"></i>
                    <span style="color:#166534;">
                        {{ $editingIndex !== null ? 'Edit Payment Method' : 'Add Payment Method' }}
                    </span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body p-4"
                 x-data="{ icon: @entangle('editingItem.icon').live }">
                <div class="row g-3">

                    {{-- Icon type picker --}}
                    <div class="col-12">
                        <label class="form-label small fw-semibold mb-2 d-block text-muted"
                               style="font-size:.68rem;letter-spacing:.05em;text-transform:uppercase;">
                            Payment Type
                        </label>
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach($payIconDefs as $iconOpt => $iconMeta)
                            <label class="d-flex flex-column align-items-center justify-content-center gap-1 rounded-3 border"
                                   style="min-width:70px;padding:.6rem .4rem;cursor:pointer;transition:all .15s;user-select:none;"
                                   x-bind:style="icon === '{{ $iconOpt }}'
                                       ? 'background:#f0fdf4;border-color:#22c55e;box-shadow:0 0 0 2px #bbf7d0;'
                                       : 'background:#fff;border-color:#e2e8f0;'"
                                   @click="icon = '{{ $iconOpt }}'">
                                <input type="radio" name="pm_icon_modal" value="{{ $iconOpt }}"
                                       wire:model="editingItem.icon" class="d-none">
                                <span style="display:flex;color:{{ $iconMeta['color'] }};">
                                    {!! $iconMeta['svg'] !!}
                                </span>
                                <span style="font-size:.63rem;font-weight:600;color:#64748b;line-height:1.2;margin-top:2px;">
                                    {{ $iconMeta['label'] }}
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
                               placeholder="Cash / UPI / Card">
                    </div>

                    {{-- Detail --}}
                    <div class="col-sm-6">
                        <label class="form-label small fw-semibold mb-1">Detail</label>
                        <input type="text" class="form-control"
                               wire:model.blur="editingItem.detail"
                               placeholder="GPay, PhonePe · +91 9XXXXXXXX">
                    </div>

                    {{-- Icon colour --}}
                    <div class="col-12">
                        <label class="form-label small fw-semibold mb-1">Icon Colour</label>
                        <div class="input-group">
                            <input type="color" class="form-control form-control-color p-0"
                                   style="max-width:44px;cursor:pointer;"
                                   wire:model.lazy="editingItem.stroke">
                            <input type="text" class="form-control font-monospace"
                                   wire:model.blur="editingItem.stroke"
                                   placeholder="#0d9488">
                        </div>
                    </div>

                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer border-top py-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-success btn-sm px-4"
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
