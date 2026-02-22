@extends('admin.layouts.app')

@section('title', 'vCards')

@section('content')
    <div class="py-3">
        <h4 class="fs-18 fw-semibold m-0">vCards Management</h4>
        <p class="text-muted small mb-0">Create and manage digital business cards</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Instructions</h6>
            <p class="text-muted mb-0">After creating vCards below, add DNS A records on GoDaddy: <code>subdomain.{{ $baseDomain }} → IP</code></p>
        </div>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0 fw-semibold">All vCards</h5>
                <div class="d-flex gap-2 align-items-center">
                    <input type="text" class="form-control form-control-sm" placeholder="Search vCards..." style="width: 250px;">
                    <a href="{{ route('admin.vcards.create') }}" class="btn btn-sm btn-primary">
                        <i class="mdi mdi-plus"></i> Add New
                    </a>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            @if ($vcards->isEmpty())
                <div class="p-5 text-center">
                    <div class="mb-3">
                        <i class="mdi mdi-folder-open-outline" style="font-size: 64px; color: #cbd5e1;"></i>
                    </div>
                    <h5 class="text-muted mb-2">No vCards Found</h5>
                    <p class="text-muted small mb-3">Get started by creating your first vCard</p>
                    <a href="{{ route('admin.vcards.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Create First vCard
                    </a>
                </div>
            @else
                <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr class="bg-light" style="border-bottom: 2px solid #e2e8f0;">
                                <th style="font-weight: 600;">Subdomain</th>
                                <th style="font-weight: 600;">Client</th>
                                <th style="font-weight: 600;">Template</th>
                                <th style="font-weight: 600;">Status</th>
                                <th style="font-weight: 600;">Created</th>
                                <th style="font-weight: 600;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vcards as $vcard)
                                <tr>
                                    <td>{{ $vcard->subdomain }}.{{ $baseDomain }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $vcard->client_name }}</div>
                                        <div class="text-muted small">{{ $vcard->client_email }}</div>
                                    </td>
                                    <td>{{ $vcard->template_key }}</td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.vcards.updateStatus', $vcard->id) }}" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 140px;">
                                                <option value="draft" @if($vcard->status === 'draft') selected @endif>Draft</option>
                                                <option value="pending_verification" @if($vcard->status === 'pending_verification') selected @endif>Pending</option>
                                                <option value="active" @if($vcard->status === 'active') selected @endif>Active</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="text-muted">{{ $vcard->created_at?->format('d M Y') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            @if ($vcard->status === 'active')
                                                <a href="{{ url('/' . $vcard->subdomain) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-circle p-0" data-bs-toggle="tooltip" title="Preview" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                                    <i class="mdi mdi-eye" style="font-size: 14px;"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('admin.vcards.edit', $vcard->id) }}" class="btn btn-sm btn-outline-warning rounded-circle p-0" data-bs-toggle="tooltip" title="Edit Details" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="mdi mdi-pencil" style="font-size: 14px;"></i>
                                            </a>
                                            @if ($vcard->user_id)
                                                <a href="{{ route('admin.vcards.data.section', $vcard->id) }}" class="btn btn-sm btn-outline-info rounded-circle p-0" data-bs-toggle="tooltip" title="Edit Content" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                                    <i class="mdi mdi-pencil-box" style="font-size: 14px;"></i>
                                                </a>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-circle p-0" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $vcard->id }}" data-bs-toggle="tooltip" title="Delete" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                                                <i class="mdi mdi-delete" style="font-size: 14px;"></i>
                                            </button>
                                        </div>
                                    </td>

                                    <!-- Delete Confirmation Modal -->
                                    <div class="modal fade" id="deleteModal{{ $vcard->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h6 class="modal-title">
                                                        <i class="mdi mdi-alert-circle"></i> Delete vCard
                                                    </h6>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="mb-2">Are you sure you want to delete this vCard?</p>
                                                    <div class="alert alert-warning small mb-0">
                                                        <strong>{{ $vcard->client_name }}</strong> ({{ $vcard->subdomain }}.{{ $baseDomain }})
                                                    </div>
                                                    <p class="text-muted small mt-2 mb-0">This action cannot be undone. All associated files will be deleted.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <form method="POST" action="{{ route('admin.vcards.destroy', $vcard) }}" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="mdi mdi-delete"></i> Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </tr>
                            @endforeach
                        </tbody>
                </table>
            @endif
        </div>
        </div>
    </div>

    <style>
        .table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.2s ease;
        }
        
        .table tbody tr:hover {
            background-color: #f8fafc !important;
        }
        
        .btn-sm.rounded-circle:hover {
            transform: scale(1.1);
            transition: all 0.2s ease;
        }
        
        .form-select-sm:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.15);
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
    </script>
@endsection
