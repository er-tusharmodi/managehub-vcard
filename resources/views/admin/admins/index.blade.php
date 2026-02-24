@extends('admin.layouts.app')

@section('title', 'Admins')

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Admins</h4>
            <p class="text-muted small mb-0">Manage administrator accounts and permissions</p>
        </div>

        <div class="text-end">
            <a href="{{ route('admin.admins.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus-circle me-2"></i>Add Admin
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-bottom px-4 py-3">
            <h5 class="mb-0 fw-semibold"><i class="mdi mdi-account-multiple me-2 text-primary"></i>All Admins</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-semibold ps-4"><i class="mdi mdi-account-outline me-2"></i>Name</th>
                            <th class="fw-semibold"><i class="mdi mdi-email-outline me-2"></i>Email</th>
                            <th class="fw-semibold">Roles</th>
                            <th class="fw-semibold text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($admins as $admin)
                            <tr class="border-bottom">
                                <td class="ps-4">
                                    <div class="fw-semibold">{{ $admin->name }}</div>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $admin->email }}</small>
                                </td>
                                <td>
                                    @foreach ($admin->roles as $role)
                                        <span class="badge bg-primary-subtle text-primary">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.admins.edit', $admin) }}" class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Edit">
                                            <i class="mdi mdi-pencil-outline"></i>
                                        </a>
                                        <button class="btn btn-outline-danger delete-admin-btn" data-admin-id="{{ $admin->id }}" data-admin-name="{{ $admin->name }}" data-bs-toggle="tooltip" title="Delete">
                                            <i class="mdi mdi-trash-can-outline"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Delete Modal for this admin -->
                            <div class="modal fade" id="deleteModal{{ $admin->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h6 class="modal-title">
                                                <i class="mdi mdi-alert-circle me-2"></i> Delete Admin
                                            </h6>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="mb-2">Are you sure you want to delete this admin account?</p>
                                            <div class="alert alert-warning small mb-0">
                                                <strong>{{ $admin->name }}</strong> ({{ $admin->email }})
                                            </div>
                                            <p class="text-muted small mt-3 mb-0">This action cannot be undone. All associated data will be removed.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <form method="POST" action="{{ route('admin.admins.destroy', $admin) }}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="mdi mdi-delete me-1"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="mdi mdi-inbox-multiple" style="font-size: 48px; color: #cbd5e1;"></i>
                                    <p class="mt-3">No admins yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        .table tbody td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }

        .btn-group-sm .btn {
            padding: 0.35rem 0.65rem;
            font-size: 0.875rem;
        }
    </style>

    <script>
        // Initialize Bootstrap tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Handle delete button clicks
            document.querySelectorAll('.delete-admin-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const adminId = this.dataset.adminId;
                    const adminName = this.dataset.adminName;
                    const modal = new bootstrap.Modal(document.getElementById(`deleteModal${adminId}`));
                    modal.show();
                });
            });
        });
    </script>
@endsection
