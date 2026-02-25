@extends('client.layouts.app')

@section('title', $typeLabel)

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">{{ $typeLabel }}</h4>
            <p class="text-muted small mb-0">{{ $vcard->client_name }} · {{ $vcard->subdomain }}</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if ($rows->isEmpty())
                <div class="text-center py-5">
                    <i class="mdi mdi-inbox text-muted" style="font-size: 40px;"></i>
                    <p class="text-muted mb-0">No {{ strtolower($typeLabel) }} received yet.</p>
                </div>
            @else
                @if($type === 'order')
                    {{-- Order specific layout --}}
                    @foreach ($rows as $row)
                        <div class="card mb-3 border" data-order-id="{{ $row->id }}">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Order #{{ $row->id }}</strong>
                                    <span class="text-muted ms-2">{{ $row->created_at->format('d M Y, h:i A') }}</span>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'accepted' => 'success',
                                            'declined' => 'danger',
                                        ];
                                        $statusColor = $statusColors[$row->status ?? 'pending'] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }} ms-2 status-badge">{{ ucfirst($row->status ?? 'pending') }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="badge bg-primary fs-6">₹{{ number_format($row->total ?? 0, 2) }}</div>
                                    @if(($row->status ?? 'pending') === 'pending')
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-success btn-update-status" data-status="accepted">
                                                <i class="mdi mdi-check"></i> Accept
                                            </button>
                                            <button type="button" class="btn btn-danger btn-update-status" data-status="declined">
                                                <i class="mdi mdi-close"></i> Decline
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th class="text-center">Quantity</th>
                                                <th class="text-end">Price</th>
                                                <th class="text-end">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $items = is_array($row->items ?? null) ? $row->items : [];
                                            @endphp
                                            @forelse($items as $item)
                                                <tr>
                                                    <td>
                                                        <div>{{ $item['name'] ?? '-' }}</div>
                                                        @if(!empty($item['brand']))
                                                            <small class="text-muted">{{ $item['brand'] }}</small>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">{{ $item['qty'] ?? '-' }}</td>
                                                    <td class="text-end">₹{{ number_format($item['price'] ?? 0, 2) }}</td>
                                                    <td class="text-end fw-semibold">₹{{ number_format($item['total'] ?? 0, 2) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">No items</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    {{-- Booking, Enquiry, Contact layout --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Message</th>
                                    @if($type !== 'contact')
                                        <th>Details</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rows as $row)
                                    @php
                                        $items = is_array($row->items ?? null) ? $row->items : [];
                                        $itemsText = collect($items)->map(function ($item) {
                                            if (is_array($item)) {
                                                $label = $item['label'] ?? '';
                                                $value = $item['value'] ?? '';
                                                return $label && $value ? ucfirst($label) . ': ' . $value : '';
                                            }
                                            return $item;
                                        })->filter()->implode(', ');
                                    @endphp
                                    <tr>
                                        <td class="text-nowrap">{{ $row->created_at->format('d M Y, h:i A') }}</td>
                                        <td>{{ $row->name ?? '-' }}</td>
                                        <td>{{ $row->phone ?? '-' }}</td>
                                        <td>{{ $row->email ?? '-' }}</td>
                                        <td class="text-muted">{{ \Illuminate\Support\Str::limit($row->message ?? '-', 80) }}</td>
                                        @if($type !== 'contact')
                                            <td class="text-muted small">{{ $itemsText !== '' ? $itemsText : '-' }}</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div class="mt-3">
                    {{ $rows->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function showActionToast(message, actionLabel, actionClass, onConfirm) {
            var container = document.getElementById('client-toast-container');
            if (!container || !message) {
                return;
            }

            var toastEl = document.createElement('div');
            toastEl.className = 'toast align-items-center text-bg-warning border-0 mb-2 fade';
            toastEl.setAttribute('role', 'alert');
            toastEl.setAttribute('aria-live', 'assertive');
            toastEl.setAttribute('aria-atomic', 'true');
            toastEl.innerHTML = '' +
                '<div class="d-flex">' +
                    '<div class="toast-body">' + message + '</div>' +
                    '<div class="d-flex align-items-center gap-2 pe-2">' +
                        '<button type="button" class="btn btn-sm ' + actionClass + ' text-white" data-confirm="true">' + actionLabel + '</button>' +
                        '<button type="button" class="btn btn-sm btn-light" data-cancel="true">Cancel</button>' +
                    '</div>' +
                '</div>';

            container.appendChild(toastEl);

            var confirmBtn = toastEl.querySelector('[data-confirm]');
            var cancelBtn = toastEl.querySelector('[data-cancel]');

            function cleanup() {
                if (toastEl && toastEl.parentNode) {
                    toastEl.remove();
                }
            }

            if (confirmBtn) {
                confirmBtn.addEventListener('click', function () {
                    cleanup();
                    if (typeof onConfirm === 'function') {
                        onConfirm();
                    }
                });
            }

            if (cancelBtn) {
                cancelBtn.addEventListener('click', function () {
                    cleanup();
                });
            }

            if (window.bootstrap && typeof bootstrap.Toast === 'function') {
                var toast = new bootstrap.Toast(toastEl, { autohide: false });
                toast.show();
            }
        }

        document.querySelectorAll('.btn-update-status').forEach(button => {
            button.addEventListener('click', async function() {
                const status = this.dataset.status;
                const card = this.closest('[data-order-id]');
                const orderId = card.dataset.orderId;
                const vcardId = {{ $vcard->id }};
                const type = '{{ $type }}';
                
                const actionLabels = {
                    'accepted': { label: 'Accept', class: 'btn-success', message: 'Are you sure you want to accept this order?' },
                    'declined': { label: 'Decline', class: 'btn-danger', message: 'Are you sure you want to decline this order?' }
                };
                
                const config = actionLabels[status];
                if (!config) return;
                
                showActionToast(config.message, config.label, config.class, async function() {
                    try {
                        button.disabled = true;
                        
                        const response = await fetch(`/vcards/${vcardId}/submissions/${type}/${orderId}/status`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ status }),
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            // Update status badge
                            const statusBadge = card.querySelector('.status-badge');
                            statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                            statusBadge.className = 'badge ms-2 status-badge bg-' + (status === 'accepted' ? 'success' : 'danger');
                            
                            // Remove action buttons
                            const btnGroup = card.querySelector('.btn-group');
                            if (btnGroup) {
                                btnGroup.remove();
                            }
                            
                            // Show success toast
                            if (window.showToast) {
                                showToast('Order status updated successfully!', 'success');
                            }
                        } else {
                            if (window.showToast) {
                                showToast('Failed to update status. Please try again.', 'error');
                            }
                            button.disabled = false;
                        }
                    } catch (error) {
                        console.error('Error updating status:', error);
                        if (window.showToast) {
                            showToast('An error occurred. Please try again.', 'error');
                        }
                        button.disabled = false;
                    }
                });
            });
        });
    });
</script>
@endpush
