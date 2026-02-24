@extends('client.layouts.app')

@section('title', 'My Dashboard')

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Dashboard</h4>
            <p class="text-muted small mb-0">Welcome back! Here's your vCard overview</p>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="row g-3 mb-4">
        <!-- Account Joined Card -->
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #4f46e5;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Account Joined</p>
                            <h4 class="mb-0 fw-bold text-primary">{{ $joinedDate ? $joinedDate->format('d M Y') : 'N/A' }}</h4>
                        </div>
                        <i class="mdi mdi-calendar" style="font-size: 32px; color: #4f46e5; opacity: 0.2;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Plan Expiry Card -->
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #f59e0b;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Plan Expires</p>
                            <h4 class="mb-0 fw-bold" style="color: #f59e0b;">
                                @if($plannedExpiry)
                                    {{ $plannedExpiry->format('d M Y') }}
                                    @if($plannedExpiry->diffInDays(now()) <= 7)
                                        <span class="badge bg-warning-light text-warning ms-2">Soon</span>
                                    @endif
                                @else
                                    Never
                                @endif
                            </h4>
                        </div>
                        <i class="mdi mdi-clock-outline" style="font-size: 32px; color: #f59e0b; opacity: 0.2;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Visitors Card -->
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #10b981;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Total Visitors</p>
                            <h4 class="mb-0 fw-bold text-success">{{ $totalVisitors }}</h4>
                        </div>
                        <i class="mdi mdi-eye" style="font-size: 32px; color: #10b981; opacity: 0.2;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active vCards Card -->
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #8b5cf6;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted small mb-1">Active vCards</p>
                            <h4 class="mb-0 fw-bold" style="color: #8b5cf6;">{{ $activeVcards }}<span class="text-muted" style="font-size: 0.7em;"> / {{ $totalVcards }}</span></h4>
                        </div>
                        <i class="mdi mdi-cards" style="font-size: 32px; color: #8b5cf6; opacity: 0.2;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- vCards Table -->
    <div class="card border-0 shadow-sm" id="vcards">
        <div class="card-header bg-white border-bottom px-4 py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h5 class="mb-0 fw-semibold">
                        <i class="mdi mdi-credit-card text-primary me-2"></i>My vCards
                    </h5>
                </div>
            </div>
        </div>

        @if ($vcards->isEmpty())
            <div class="card-body">
                <div class="text-center py-12">
                    <div class="mb-3">
                        <i class="mdi mdi-cards-variant" style="font-size: 48px; color: #d1d5db;"></i>
                    </div>
                    <h5 class="text-muted mb-2">No vCards Yet</h5>
                    <p class="text-muted small">You don't have any vCards. Contact your administrator to create one.</p>
                </div>
            </div>
        @else
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4 fw-semibold text-muted">vCard Name</th>
                                <th class="fw-semibold text-muted">Status</th>
                                <th class="fw-semibold text-muted">Template</th>
                                <th class="fw-semibold text-muted">URL</th>
                                <th class="fw-semibold text-muted">Visitors</th>
                                <th class="fw-semibold text-muted">Last Updated</th>
                                <th class="fw-semibold text-muted text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vcards as $vcard)
                                <tr class="border-bottom hover-row" style="cursor: pointer;">
                                    <td class="ps-4">
                                        <div class="fw-semibold">{{ $vcard->client_name }}</div>
                                        <small class="text-muted">{{ $vcard->client_email }}</small>
                                    </td>
                                    <td>
                                        @if($vcard->status === 'active')
                                            <span class="badge bg-success-light text-success"><i class="mdi mdi-check-circle me-1"></i>Active</span>
                                        @elseif($vcard->status === 'draft')
                                            <span class="badge bg-secondary-light text-secondary"><i class="mdi mdi-file-document me-1"></i>Draft</span>
                                        @elseif($vcard->status === 'pending_verification')
                                            <span class="badge bg-warning-light text-warning"><i class="mdi mdi-clock me-1"></i>Pending</span>
                                        @else
                                            <span class="badge bg-danger-light text-danger"><i class="mdi mdi-close-circle me-1"></i>Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ ucfirst($vcard->template_key) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ url('/' . $vcard->subdomain) }}" target="_blank" class="text-primary text-decoration-none small" title="Visit vCard">
                                            <i class="mdi mdi-link"></i> {{ $vcard->subdomain }}.{{ config('vcard.base_domain') }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-semibold">{{ $visitorsByVcard[$vcard->id]['total'] ?? 0 }}</span>
                                            <small class="text-muted">
                                                Today: {{ $visitorsByVcard[$vcard->id]['today'] ?? 0 }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $vcard->updated_at->format('d M Y') }}</small>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('vcard.editor', $vcard->subdomain) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Edit vCard">
                                                <i class="mdi mdi-pencil-outline"></i>
                                            </a>
                                            <a href="{{ url('/' . $vcard->subdomain) }}" target="_blank" class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip" title="View vCard">
                                                <i class="mdi mdi-eye-outline"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center flex-wrap gap-2 px-4 py-3">
                <small class="text-muted">Showing {{ $vcards->count() }} vCard{{ $vcards->count() !== 1 ? 's' : '' }}</small>
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

        .badge {
            font-weight: 500;
        }
    </style>
@endsection
