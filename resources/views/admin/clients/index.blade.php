@extends('admin.layouts.app')

@section('title', 'Clients')

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Clients Management</h4>
            <p class="text-muted small mb-0">View and manage all clients with their vCard details</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #4f46e5;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Total Clients</p>
                            <h3 class="mb-0 fw-bold text-primary">{{ $totalClients }}</h3>
                        </div>
                        <i class="mdi mdi-people" style="font-size: 32px; color: #4f46e5; opacity: 0.2;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #10b981;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Active Subscriptions</p>
                            <h3 class="mb-0 fw-bold text-success">{{ $activeSubscriptions }}</h3>
                        </div>
                        <i class="mdi mdi-check-decagram" style="font-size: 32px; color: #10b981; opacity: 0.2;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #f59e0b;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Inactive</p>
                            <h3 class="mb-0 fw-bold" style="color: #f59e0b;">{{ $totalClients - $activeSubscriptions }}</h3>
                        </div>
                        <i class="mdi mdi-alert-outline" style="font-size: 32px; color: #f59e0b; opacity: 0.2;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
        $getSortUrl = function($column) use ($sort, $direction) {
            $newDirection = ($sort === $column && $direction === 'asc') ? 'desc' : 'asc';
            return route('admin.clients.index', ['sort' => $column, 'direction' => $newDirection]);
        };
        
        $getSortIcon = function($column) use ($sort, $direction) {
            if ($sort === $column) {
                return $direction === 'asc' ? '↑' : '↓';
            }
            return '';
        };
    @endphp

    <!-- Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom px-4 py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h5 class="mb-0 fw-semibold">
                        <i class="mdi mdi-people text-primary me-2"></i>All Clients
                    </h5>
                    <small class="text-muted">Page {{ $clients->currentPage() }} of {{ $clients->lastPage() }}</small>
                </div>
                <div class="flex-grow-1"></div>
                <input type="text" class="form-control form-control-md" placeholder="Search clients..." style="max-width: 250px;" id="searchInput">
            </div>
        </div>

        <div class="table-responsive">
            @if ($clients->isEmpty())
                <div class="text-center py-5">
                    <i class="mdi mdi-inbox-multiple" style="font-size: 64px; color: #cbd5e1;"></i>
                    <h5 class="text-muted mt-3">No Clients Found</h5>
                    <p class="text-muted small">Clients will appear here once vCards are created</p>
                </div>
            @else
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-semibold ps-4">
                                <a href="{{ $getSortUrl('username') }}" class="text-decoration-none text-dark d-flex align-items-center gap-2">
                                    <i class="mdi mdi-account-key"></i> Username {{ $getSortIcon('username') }}
                                </a>
                            </th>
                            <th class="fw-semibold">
                                <a href="{{ $getSortUrl('name') }}" class="text-decoration-none text-dark d-flex align-items-center gap-2">
                                    <i class="mdi mdi-account-outline"></i> Name {{ $getSortIcon('name') }}
                                </a>
                            </th>
                            <th class="fw-semibold">
                                <a href="{{ $getSortUrl('email') }}" class="text-decoration-none text-dark d-flex align-items-center gap-2">
                                    <i class="mdi mdi-email-outline"></i> Email {{ $getSortIcon('email') }}
                                </a>
                            </th>
                            <th class="fw-semibold">vCards</th>
                            <th class="fw-semibold">Subdomains</th>
                            <th class="fw-semibold">Status</th>
                            <th class="fw-semibold">
                                <a href="{{ $getSortUrl('created_at') }}" class="text-decoration-none text-dark d-flex align-items-center gap-2">
                                    <i class="mdi mdi-calendar-today"></i> Created {{ $getSortIcon('created_at') }}
                                </a>
                            </th>
                            <th class="fw-semibold text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clients as $client)
                            <tr class="border-bottom hover-row" style="cursor: pointer; transition: all 0.2s ease;">
                                <td class="ps-4">
                                    <span class="badge bg-info text-white fs-6 px-3 py-2">{{ $client->username ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $client->name }}</div>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $client->email }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $client->vcards->count() }} vCard{{ $client->vcards->count() !== 1 ? 's' : '' }}</span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1" style="max-width: 250px;">
                                        @foreach ($client->vcards->take(2) as $vcard)
                                            @php
                                                $fullDomain = $vcard->subdomain . '.' . $baseDomain;
                                            @endphp
                                            <div class="d-flex align-items-center gap-2">
                                                <a href="https://{{ $fullDomain }}" target="_blank" class="text-primary text-decoration-none small" title="Open vCard">
                                                    <i class="mdi mdi-link"></i> {{ $fullDomain }}
                                                </a>
                                                <button type="button" class="btn btn-link btn-sm p-0 copy-subdomain-btn text-muted" data-subdomain="{{ $fullDomain }}" title="Copy">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                        @if ($client->vcards->count() > 2)
                                            <small class="text-muted">+{{ $client->vcards->count() - 2 }} more</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $activeVcards = $client->vcards->where('subscription_status', 'active')->count();
                                    @endphp
                                    @if ($activeVcards > 0)
                                        <span class="badge bg-success-subtle text-success d-inline-flex align-items-center gap-1">
                                            <i class="mdi mdi-check-circle-outline"></i> Active
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary d-inline-flex align-items-center gap-1">
                                            <i class="mdi mdi-close-circle-outline"></i> Inactive
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $client->created_at?->format('d M Y') }}</small>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex" role="group" style="gap: 0.4rem !important;">
                                        <a href="{{ route('admin.vcards.index') }}?sort=created_at&direction=desc&client={{ $client->id }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="View vCards">
                                            <i class="mdi mdi-eye-outline"></i>
                                        </a>
                                        <a href="mailto:{{ $client->email }}" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Email Client">
                                            <i class="mdi mdi-email-outline"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        @if (!$clients->isEmpty())
            <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center flex-wrap gap-2 px-4 py-3">
                <small class="text-muted">Showing {{ $clients->firstItem() }} to {{ $clients->lastItem() }} of {{ $clients->total() }} results</small>
                <div>
                    {{ $clients->links('pagination::bootstrap-5') }}
                </div>
            </div>
        @endif
    </div>

    <style>
        .hover-row:hover {
            background-color: #f8fafc !important;
        }

        .table thead th {
            background-color: #f3f4f6;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
        }

        .table tbody td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }

        .copy-subdomain-btn:hover {
            color: #4f46e5 !important;
        }

        .badge {
            font-weight: 500;
        }
    </style>

    <script>
        // Initialize Bootstrap tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        document.getElementById('searchInput')?.addEventListener('keyup', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('tbody tr').forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Copy subdomain functionality
        document.addEventListener('click', function(e) {
            if (e.target.closest('.copy-subdomain-btn')) {
                e.preventDefault();
                const btn = e.target.closest('.copy-subdomain-btn');
                const subdomain = btn.getAttribute('data-subdomain');
                
                navigator.clipboard.writeText(subdomain).then(() => {
                    const originalIcon = btn.innerHTML;
                    btn.innerHTML = '<i class="mdi mdi-check" style="font-size: 14px; color: #28a745;"></i>';
                    
                    setTimeout(() => {
                        btn.innerHTML = originalIcon;
                    }, 2000);
                }).catch(err => {
                    console.error('Failed to copy:', err);
                });
            }
        });
    </script>
@endsection
