@extends('client.layouts.app')

@section('title', 'Leads — ' . $vcard->client_name)

@push('styles')
<style>
    /* ── Stat Cards ──────────────────────────────────────────────── */
    .lead-stat-card {
        border-radius: 12px;
        border: 1px solid #f1f3f5;
        transition: transform .15s, box-shadow .15s;
        text-decoration: none !important;
    }
    .lead-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,.09) !important;
    }
    .lead-stat-icon {
        width: 44px; height: 44px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    /* ── Tab Nav ─────────────────────────────────────────────────── */
    .leads-tab-nav {
        gap: 2px;
        border-bottom: 1px solid #e9ecef;
    }
    .leads-tab-nav .nav-link {
        border: none;
        border-radius: 0;
        border-bottom: 2px solid transparent;
        padding: 10px 16px;
        color: #6c757d;
        font-size: 0.875rem;
        transition: color .15s, border-color .15s;
    }
    .leads-tab-nav .nav-link:hover { color: #343a40; }
    .leads-tab-nav .nav-link.active {
        color: #000;
        font-weight: 600;
        background: transparent;
        border-bottom-color: currentColor;
    }
    /* ── Table ───────────────────────────────────────────────────── */
    .leads-table thead th {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #9ca3af;
        font-weight: 600;
        background: #f9fafb;
        border-bottom: 1px solid #e9ecef;
        padding: 10px 16px;
        white-space: nowrap;
    }
    .leads-table tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: background .1s;
    }
    .leads-table tbody tr:hover { background: #f9fafb; }
    .leads-table tbody tr:last-child { border-bottom: none; }
    .leads-table tbody td {
        padding: 13px 16px;
        vertical-align: middle;
        font-size: 0.875rem;
    }
    .leads-avatar {
        width: 34px; height: 34px;
        border-radius: 50%;
        font-size: 13px; font-weight: 700;
        display: inline-flex; align-items: center; justify-content: center;
        color: #fff; flex-shrink: 0;
    }
    /* ── Order Cards ─────────────────────────────────────────────── */
    .order-card {
        border-radius: 12px;
        border: 1px solid #e9ecef;
        overflow: hidden;
        transition: box-shadow .15s;
    }
    .order-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.07); }
    .order-card-header {
        background: #f9fafb;
        border-bottom: 1px solid #e9ecef;
        padding: 10px 16px;
    }
    .order-meta-label {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #9ca3af;
        margin-bottom: 2px;
    }
    /* ── Misc ────────────────────────────────────────────────────── */
    .detail-badge {
        font-size: 0.72rem;
        background: #f3f4f6;
        color: #374151;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 2px 7px;
        display: inline-block;
        margin: 2px 2px 2px 0;
    }
    .empty-icon-wrap {
        width: 72px; height: 72px;
        border-radius: 50%;
        background: #f3f4f6;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 16px;
        font-size: 30px; color: #9ca3af;
    }
    .status-pill {
        font-size: 0.7rem;
        padding: 3px 9px;
        border-radius: 20px;
        font-weight: 600;
        letter-spacing: .02em;
    }
</style>
@endpush

@section('content')

    {{-- ─── Page Header ──────────────────────────────────────────── --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
        <div>
            <h4 class="fs-18 fw-semibold mb-0">Leads</h4>
            <p class="text-muted small mb-0 mt-1">
                <span class="fw-medium text-dark">{{ $vcard->client_name }}</span>
                <span class="text-muted mx-1">·</span>
                <a href="{{ vcard_public_url($vcard->subdomain) }}" target="_blank" class="text-muted text-decoration-none">
                    {{ $vcard->subdomain }}<i class="mdi mdi-open-in-new fs-11 ms-1"></i>
                </a>
            </p>
        </div>
        <a href="{{ route('vcard.editor', $vcard->subdomain) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
            <i class="mdi mdi-pencil-outline me-1"></i>Edit vCard
        </a>
    </div>

    {{-- ─── Stat Cards ────────────────────────────────────────────── --}}
    @php
        $tabMeta = [
            'order'   => ['color' => '#ef4444', 'bg' => '#fef2f2', 'icon' => 'mdi-cart-outline'],
            'booking' => ['color' => '#3b82f6', 'bg' => '#eff6ff', 'icon' => 'mdi-calendar-check-outline'],
            'enquiry' => ['color' => '#f59e0b', 'bg' => '#fffbeb', 'icon' => 'mdi-help-circle-outline'],
            'contact' => ['color' => '#10b981', 'bg' => '#ecfdf5', 'icon' => 'mdi-message-text-outline'],
        ];
    @endphp
    <div class="row g-3 mb-4">
        @foreach ($tabLabels as $tab => $label)
            @php $m = $tabMeta[$tab]; $isActive = ($activeTab === $tab); @endphp
            <div class="col-6 col-xl-3">
                <a href="{{ route('client.leads', $vcard->subdomain) }}?tab={{ $tab }}"
                   class="card border-0 shadow-sm h-100 lead-stat-card"
                   style="{{ $isActive ? 'border:1px solid ' . $m['color'] . ';box-shadow:0 0 0 1px ' . $m['color'] . '22,0 4px 14px rgba(0,0,0,.07)!important;' : '' }}">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="lead-stat-icon" style="background:{{ $m['bg'] }};color:{{ $m['color'] }};">
                                <i class="mdi {{ $m['icon'] }}"></i>
                            </div>
                            <div>
                                <p class="text-muted mb-0" style="font-size:0.75rem;">{{ $label }}</p>
                                <h4 class="mb-0 fw-bold lh-1 mt-1" style="color:{{ $m['color'] }};">{{ $counts[$tab] ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    {{-- ─── Main Panel ────────────────────────────────────────────── --}}
    <div class="row g-3 align-items-start">

        {{-- Sidebar Tab Nav --}}
        <div class="col-12 col-md-3 col-xl-2">
            <div class="card border-0 shadow-sm" style="border-radius:14px;overflow:hidden;position:sticky;top:80px;">
                <div class="card-body p-0">
                    @foreach ($tabLabels as $tab => $label)
                        @php $m = $tabMeta[$tab]; $isActive = ($activeTab === $tab); @endphp
                        <a href="{{ route('client.leads', $vcard->subdomain) }}?tab={{ $tab }}"
                           class="d-flex align-items-center gap-2 px-3 py-3 border-bottom text-decoration-none"
                           style="border-left:3px solid {{ $isActive ? $m['color'] : 'transparent' }};background:{{ $isActive ? $m['color'].'0d' : 'transparent' }};color:{{ $isActive ? $m['color'] : '#6c757d' }};transition:background .15s;">
                            <div style="width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:15px;flex-shrink:0;background:{{ $m['bg'] }};color:{{ $m['color'] }};">
                                <i class="mdi {{ $m['icon'] }}"></i>
                            </div>
                            <span class="fw-{{ $isActive ? 'bold' : 'medium' }} flex-grow-1" style="font-size:.875rem;">{{ $label }}</span>
                            @if(($counts[$tab] ?? 0) > 0)
                                <span class="flex-shrink-0" style="background:{{ $m['color'] }}1a;color:{{ $m['color'] }};font-size:.65rem;padding:2px 8px;border-radius:20px;font-weight:700;">
                                    {{ $counts[$tab] > 99 ? '99+' : $counts[$tab] }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Content Area --}}
        <div class="col-12 col-md-9 col-xl-10">
            <div class="card border-0 shadow-sm" style="border-radius:14px;overflow:hidden;">

        {{-- Body --}}
        <div style="min-height:220px;">

            @php $activeColor = $tabMeta[$activeTab]['color']; $activeBg = $tabMeta[$activeTab]['bg']; @endphp

            {{-- ── Empty State ── --}}
            @if ($rows->isEmpty())
                <div class="text-center py-5 px-4">
                    <div class="empty-icon-wrap">
                        <i class="mdi {{ $tabMeta[$activeTab]['icon'] }}"></i>
                    </div>
                    <h6 class="fw-semibold mb-1">No {{ strtolower($tabLabels[$activeTab]) }} yet</h6>
                    <p class="text-muted small mb-3" style="max-width:340px;margin:0 auto 16px;">
                        When customers submit {{ strtolower($tabLabels[$activeTab]) }} through your vCard, they'll appear here.
                    </p>
                    <a href="{{ vcard_public_url($vcard->subdomain) }}" target="_blank"
                       class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="mdi mdi-open-in-new me-1"></i>View Your vCard
                    </a>
                </div>

            {{-- ── Orders ── --}}
            @elseif ($activeTab === 'order')
                <div class="p-3 p-md-4">
                    @foreach ($rows as $row)
                        @php
                            $statusColors = [
                                'pending'  => ['bg' => '#fff7ed', 'color' => '#ea580c', 'label' => 'Pending'],
                                'accepted' => ['bg' => '#f0fdf4', 'color' => '#16a34a', 'label' => 'Accepted'],
                                'declined' => ['bg' => '#fef2f2', 'color' => '#dc2626', 'label' => 'Declined'],
                            ];
                            $sc    = $statusColors[$row->status ?? 'pending'] ?? $statusColors['pending'];
                            $num   = (($rows->currentPage() - 1) * 20) + $loop->iteration;
                            $init  = strtoupper(substr($row->name ?? ($row->payload['name'] ?? 'X'), 0, 1));
                            $pal   = ['#ef4444','#3b82f6','#f59e0b','#10b981','#8b5cf6','#ec4899'];
                            $aClr  = $pal[ord($init) % count($pal)];
                            $rawItems = $row->items ?? ($row->payload['items'] ?? null);
                            $items    = is_array($rawItems) ? $rawItems : [];
                            $rName    = $row->name    ?? ($row->payload['name']    ?? null);
                            $rPhone   = $row->phone   ?? ($row->payload['phone']   ?? null);
                            $rEmail   = $row->email   ?? ($row->payload['email']   ?? null);
                            $rNote    = $row->message ?? ($row->payload['message'] ?? null);
                            $rTotal   = $row->total   ?? ($row->payload['total']   ?? 0);
                        @endphp
                        <div class="order-card mb-3" data-order-id="{{ $row->id }}">

                            <div class="order-card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="text-muted fw-semibold" style="font-size:0.8rem;">#{{ $num }}</span>
                                    <span class="status-pill status-badge"
                                          style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }};">
                                        {{ $sc['label'] }}
                                    </span>
                                    <span class="text-muted" style="font-size:0.8rem;">
                                        <i class="mdi mdi-clock-outline me-1"></i>{{ $row->created_at->format('d M Y, h:i A') }}
                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold" style="font-size:0.95rem;color:#111;">
                                        ₹{{ number_format($rTotal, 2) }}
                                    </span>
                                    @if(($row->status ?? 'pending') === 'pending')
                                        <button type="button" class="btn btn-sm btn-success rounded-pill px-3 btn-update-status" data-status="accepted">
                                            <i class="mdi mdi-check me-1"></i>Accept
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger rounded-pill px-3 btn-update-status" data-status="declined">
                                            <i class="mdi mdi-close me-1"></i>Decline
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <div class="p-3">
                                <div class="d-flex align-items-start gap-3 flex-wrap">
                                    <div class="leads-avatar flex-shrink-0" style="background:{{ $aClr }};">{{ $init }}</div>
                                    <div class="d-flex gap-4 flex-wrap flex-grow-1">
                                        <div>
                                            <p class="order-meta-label mb-1">Customer</p>
                                            <p class="mb-0 fw-semibold">{{ $rName ?? '—' }}</p>
                                        </div>
                                        @if($rPhone)
                                        <div>
                                            <p class="order-meta-label mb-1">Phone</p>
                                            <p class="mb-0"><a href="tel:{{ $rPhone }}" class="text-decoration-none text-dark">{{ $rPhone }}</a></p>
                                        </div>
                                        @endif
                                        @if($rEmail)
                                        <div>
                                            <p class="order-meta-label mb-1">Email</p>
                                            <p class="mb-0 small"><a href="mailto:{{ $rEmail }}" class="text-decoration-none text-dark">{{ $rEmail }}</a></p>
                                        </div>
                                        @endif
                                        @if($rNote)
                                        <div class="flex-grow-1">
                                            <p class="order-meta-label mb-1">Note</p>
                                            <p class="mb-0 text-muted small">{{ $rNote }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if(!empty($items))
                                <div class="table-responsive" style="border-top:1px solid #e9ecef;">
                                    <table class="table table-sm mb-0">
                                        <thead style="background:#f9fafb;">
                                            <tr>
                                                <th class="ps-3" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;font-weight:600;padding:8px 12px;">Product</th>
                                                <th class="text-center" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;font-weight:600;padding:8px 12px;">Qty</th>
                                                <th class="text-end" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;font-weight:600;padding:8px 12px;">Price</th>
                                                <th class="text-end pe-3" style="font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;color:#9ca3af;font-weight:600;padding:8px 12px;">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($items as $item)
                                            <tr style="border-bottom:1px solid #f3f4f6;">
                                                <td class="ps-3 py-2">
                                                    <div class="fw-medium">{{ $item['name'] ?? '—' }}</div>
                                                    @if(!empty($item['brand']))<small class="text-muted">{{ $item['brand'] }}</small>@endif
                                                </td>
                                                <td class="text-center py-2">{{ $item['qty'] ?? '—' }}</td>
                                                <td class="text-end py-2 text-muted">₹{{ number_format($item['price'] ?? 0, 2) }}</td>
                                                <td class="text-end pe-3 py-2 fw-semibold">₹{{ number_format($item['total'] ?? 0, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                        </div>
                    @endforeach
                </div>

            {{-- ── Bookings / Enquiries / Contacts ── --}}
            @else
                <div class="table-responsive">
                    <table class="table leads-table mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Date</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                @if($activeTab !== 'contact')
                                    <th>Details</th>
                                @endif
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                @php
                                    $rawItems = $row->items ?? ($row->payload['items'] ?? null);
                                    $items    = is_array($rawItems) ? $rawItems : [];
                                    $rPhone   = $row->phone   ?? ($row->payload['phone']   ?? null);
                                    $rEmail   = $row->email   ?? ($row->payload['email']   ?? null);
                                    $rMsg     = $row->message ?? ($row->payload['message'] ?? null);
                                    $rName    = $row->name    ?? ($row->payload['name']    ?? null);
                                    $init2    = strtoupper(substr($rName ?? 'X', 0, 1));
                                    $pal2     = ['#ef4444','#3b82f6','#f59e0b','#10b981','#8b5cf6','#ec4899'];
                                    $aClr2    = $pal2[ord($init2) % count($pal2)];
                                @endphp
                                <tr>
                                    <td class="ps-4 text-nowrap">
                                        <span class="fw-medium" style="font-size:0.82rem;">{{ $row->created_at->format('d M Y') }}</span><br>
                                        <span class="text-muted" style="font-size:0.75rem;">{{ $row->created_at->format('h:i A') }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="leads-avatar" style="background:{{ $aClr2 }};width:30px;height:30px;font-size:11px;">{{ $init2 }}</div>
                                            <span class="fw-semibold">{{ $rName ?? '—' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($rPhone)
                                            <a href="tel:{{ $rPhone }}" class="text-decoration-none text-dark">{{ $rPhone }}</a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($rEmail)
                                            <a href="mailto:{{ $rEmail }}" class="text-decoration-none text-dark" style="font-size:0.83rem;">{{ $rEmail }}</a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    @if($activeTab !== 'contact')
                                        <td style="max-width:200px;">
                                            @if(!empty($items))
                                                @foreach($items as $item)
                                                    @if(is_array($item) && ($item['value'] ?? '') !== '')
                                                        <span class="detail-badge">{{ $item['label'] ?? '' }}: {{ $item['value'] }}</span>
                                                    @endif
                                                @endforeach
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    @endif
                                    <td class="text-muted" style="max-width:220px;font-size:0.83rem;">
                                        {{ \Illuminate\Support\Str::limit($rMsg ?? '—', 100) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>{{-- /.body --}}

        <div class="px-4 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2"
             style="border-top:1px solid #f3f4f6;background:#fafafa;border-radius:0 0 14px 14px;">
            <small class="text-muted">
                @if($rows->total() > 0)
                    Showing {{ $rows->firstItem() }}–{{ $rows->lastItem() }} of <strong>{{ $rows->total() }}</strong> {{ strtolower($tabLabels[$activeTab]) }}
                @else
                    No {{ strtolower($tabLabels[$activeTab]) }} found
                @endif
            </small>
            @if($rows->hasPages())
                {{ $rows->links() }}
            @endif
        </div>

            </div>{{-- /.card --}}
        </div>{{-- /.col-content --}}

    </div>{{-- /.row --}}

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    function showActionToast(message, actionLabel, actionClass, onConfirm) {
        var container = document.getElementById('client-toast-container');
        if (!container) return;
        var toastEl = document.createElement('div');
        toastEl.className = 'toast align-items-center text-bg-warning border-0 mb-2 fade';
        toastEl.setAttribute('role', 'alert');
        toastEl.setAttribute('aria-live', 'assertive');
        toastEl.setAttribute('aria-atomic', 'true');
        toastEl.innerHTML = '<div class="d-flex">' +
            '<div class="toast-body">' + message + '</div>' +
            '<div class="d-flex align-items-center gap-2 pe-2">' +
                '<button type="button" class="btn btn-sm ' + actionClass + ' text-white" data-confirm="true">' + actionLabel + '</button>' +
                '<button type="button" class="btn btn-sm btn-light" data-cancel="true">Cancel</button>' +
            '</div></div>';
        container.appendChild(toastEl);
        var confirmBtn = toastEl.querySelector('[data-confirm]');
        var cancelBtn  = toastEl.querySelector('[data-cancel]');
        function cleanup() { if (toastEl && toastEl.parentNode) toastEl.remove(); }
        confirmBtn && confirmBtn.addEventListener('click', function () { cleanup(); typeof onConfirm === 'function' && onConfirm(); });
        cancelBtn  && cancelBtn.addEventListener('click', cleanup);
        if (window.bootstrap && typeof bootstrap.Toast === 'function') {
            new bootstrap.Toast(toastEl, { autohide: false }).show();
        }
    }

    document.querySelectorAll('.btn-update-status').forEach(function (button) {
        button.addEventListener('click', function () {
            var status  = this.dataset.status;
            var card    = this.closest('[data-order-id]');
            var orderId = card.dataset.orderId;
            var vcardId = '{{ $vcard->id }}';
            var configs = {
                accepted: { label: 'Accept', cls: 'btn-success', msg: 'Accept this order?' },
                declined: { label: 'Decline', cls: 'btn-danger',  msg: 'Decline this order?' }
            };
            var cfg = configs[status];
            if (!cfg) return;
            showActionToast(cfg.msg, cfg.label, cfg.cls, async function () {
                try {
                    button.disabled = true;
                    var resp = await fetch('/vcards/' + vcardId + '/submissions/order/' + orderId + '/status', {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ status: status }),
                    });
                    var result = await resp.json();
                    if (result.success) {
                        var badge = card.querySelector('.status-badge');
                        badge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                        var sc = { accepted: { bg:'#f0fdf4', color:'#16a34a' }, declined: { bg:'#fef2f2', color:'#dc2626' } }[status];
                        badge.style.background = sc.bg;
                        badge.style.color = sc.color;
                        card.querySelectorAll('.btn-update-status').forEach(function(b){ b.remove(); });
                        window.showToast && window.showToast('Order updated!', 'success');
                    } else {
                        window.showToast && window.showToast('Failed to update. Try again.', 'error');
                        button.disabled = false;
                    }
                } catch (e) {
                    window.showToast && window.showToast('An error occurred.', 'error');
                    button.disabled = false;
                }
            });
        });
    });
});
</script>

@endpush
