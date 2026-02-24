@extends('admin.layouts.app')

@section('title', 'vCards')

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">vCards Management</h4>
            <p class="text-muted small mb-0">Create and manage digital business cards</p>
        </div>
        <div class="text-end">
            <a href="{{ route('admin.vcards.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus-circle me-2"></i>Add New vCard
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-decagram me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="mdi mdi-information-outline me-2"></i>
        <strong>DNS Setup:</strong> After creating vCards, add DNS A records: <code>subdomain.{{ $baseDomain }} → Your IP</code>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    @php
        $getSortUrl = function($column) use ($sort, $direction) {
            $newDirection = ($sort === $column && $direction === 'asc') ? 'desc' : 'asc';
            return route('admin.vcards.index', ['sort' => $column, 'direction' => $newDirection]);
        };
        
        $getSortIcon = function($column) use ($sort, $direction) {
            if ($sort === $column) {
                return $direction === 'asc' ? '↑' : '↓';
            }
            return '';
        };
    @endphp

    <!-- Main Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom px-4 py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h5 class="mb-0 fw-semibold">
                        <i class="mdi mdi-card-multiple text-primary me-2"></i>All vCards
                    </h5>
                    <small class="text-muted">Page {{ $vcards->currentPage() }} of {{ $vcards->lastPage() }}</small>
                </div>
                <div class="flex-grow-1"></div>
                <input type="text" class="form-control form-control-md" placeholder="Search vCards..." style="max-width: 250px;">
            </div>
        </div>

        <div class="table-responsive">
            @if ($vcards->isEmpty())
                <div class="text-center py-5">
                    <i class="mdi mdi-inbox-multiple" style="font-size: 64px; color: #cbd5e1;"></i>
                    <h5 class="text-muted mt-3">No vCards Found</h5>
                    <p class="text-muted small mb-3">Get started by creating your first vCard</p>
                    <a href="{{ route('admin.vcards.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus-circle me-2"></i>Create First vCard
                    </a>
                </div>
            @else
                <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="fw-semibold ps-4">
                                    <a href="{{ $getSortUrl('subdomain') }}" class="text-decoration-none text-dark d-flex align-items-center gap-2">
                                        <i class="mdi mdi-web"></i> Subdomain {{ $getSortIcon('subdomain') }}
                                    </a>
                                </th>
                                <th class="fw-semibold">Client Info</th>
                                <th class="fw-semibold">Template</th>
                                <th class="fw-semibold">
                                    <a href="{{ $getSortUrl('status') }}" class="text-decoration-none text-dark d-flex align-items-center gap-2">
                                        <i class="mdi mdi-information-outline"></i> Status {{ $getSortIcon('status') }}
                                    </a>
                                </th>
                                <th class="fw-semibold">
                                    <a href="{{ $getSortUrl('subscription_status') }}" class="text-decoration-none text-dark d-flex align-items-center gap-2">
                                        <i class="mdi mdi-check-circle-outline"></i> Subscription {{ $getSortIcon('subscription_status') }}
                                    </a>
                                </th>
                                <th class="fw-semibold">
                                    <a href="{{ $getSortUrl('created_at') }}" class="text-decoration-none text-dark d-flex align-items-center gap-2">
                                        <i class="mdi mdi-calendar-today"></i> Created {{ $getSortIcon('created_at') }}
                                    </a>
                                </th>
                                <th class="fw-semibold text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vcards as $vcard)
                                <tr class="border-bottom hover-row" style="cursor: pointer; transition: all 0.2s ease;">
                                    <td class="ps-4">
                                        <div class="d-flex flex-column gap-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-light text-dark px-3 py-2 fw-semibold" style="font-size: 0.95em;">
                                                    <i class="mdi mdi-web me-1"></i>{{ $vcard->subdomain }}.{{ $baseDomain }}
                                                </span>
                                                <button type="button" class="btn btn-link btn-sm p-0 copy-subdomain-btn text-muted" data-subdomain="{{ $vcard->subdomain }}.{{ $baseDomain }}" title="Copy full domain">
                                                    <i class="mdi mdi-content-copy"></i>
                                                </button>
                                            </div>
                                            <a href="https://{{ $vcard->subdomain }}.{{ $baseDomain }}" target="_blank" class="text-primary text-decoration-none" style="font-size: 0.85em;">
                                                <i class="mdi mdi-open-in-new me-1" style="font-size: 0.9em;"></i>Open vCard
                                            </a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $vcard->client_name }}</div>
                                        <small class="text-muted">{{ $vcard->client_email }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary">{{ $vcard->template_key }}</span>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('admin.vcards.updateStatus', $vcard->id) }}" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 140px;">
                                                <option value="draft" @if($vcard->status === 'draft') selected @endif><i class="mdi mdi-file-document-outline"></i> Draft</option>
                                                <option value="pending_verification" @if($vcard->status === 'pending_verification') selected @endif><i class="mdi mdi-clock-outline"></i> Pending</option>
                                                <option value="active" @if($vcard->status === 'active') selected @endif><i class="mdi mdi-check-decagram"></i> Active</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        @php
                                            $isSubActive = $vcard->subscription_status === 'active';
                                        @endphp
                                        @if ($isSubActive)
                                            <span class="badge bg-success-subtle text-success d-inline-flex align-items-center gap-1 px-3 py-2">
                                                <i class="mdi mdi-check-circle-outline"></i> Active
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger d-inline-flex align-items-center gap-1 px-3 py-2">
                                                <i class="mdi mdi-close-circle-outline"></i> Inactive
                                            </span>
                                        @endif
                                        @if ($vcard->subscription_expires_at)
                                            <div class="small text-muted mt-1">Exp: {{ $vcard->subscription_expires_at->format('d M Y') }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $vcard->created_at?->format('d M Y') }}</small>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex" role="group" style="gap: 0.4rem !important;">
                                            @if ($vcard->status === 'active')
                                                <a href="{{ url('/' . $vcard->subdomain) }}" target="_blank" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Preview vCard">
                                                    <i class="mdi mdi-eye-outline"></i>
                                                </a>
                                            @endif
                                            @if ($vcard->user_id)
                                                <button type="button" class="btn btn-sm btn-outline-success share-btn" data-vcard-id="{{ $vcard->id }}" data-bs-toggle="tooltip" title="Share Credentials with Client">
                                                    <i class="mdi mdi-share-outline"></i>
                                                </button>
                                            @endif
                                            <a href="{{ route('admin.vcards.edit', $vcard->id) }}" class="btn btn-sm btn-outline-warning" data-bs-toggle="tooltip" title="Edit vCard Details">
                                                <i class="mdi mdi-pencil-outline"></i>
                                            </a>
                                            @if ($vcard->user_id)
                                                <a href="{{ route('admin.vcards.data.section', $vcard->id) }}" class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip" title="Edit vCard Content">
                                                    <i class="mdi mdi-file-document-outline"></i>
                                                </a>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $vcard->id }}" data-bs-toggle="tooltip" title="Delete vCard">
                                                <i class="mdi mdi-trash-can-outline"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                    <!-- Delete Confirmation Modal -->
                                    <div class="modal fade" id="deleteModal{{ $vcard->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h6 class="modal-title">
                                                        <i class="mdi mdi-alert-circle me-2"></i> Delete vCard
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

                                    <!-- Share Credentials Modal -->
                                    <div class="modal fade" id="shareModal{{ $vcard->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success text-white">
                                                    <h6 class="modal-title">
                                                        <i class="mdi mdi-share-variant"></i> Share Credentials
                                                    </h6>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="alert alert-info small mb-3">
                                                        <i class="mdi mdi-information"></i> Share login credentials with <strong>{{ $vcard->client_name }}</strong>
                                                    </div>
                                                    
                                                    <!-- Username Field -->
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Username</label>
                                                        <div class="input-group">
                                                            <input type="text" class="form-control form-control-sm username-field" value="" readonly>
                                                            <button class="btn btn-sm btn-outline-secondary copy-username" type="button" title="Copy">
                                                                <i class="mdi mdi-content-copy"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Password Field -->
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Password</label>
                                                        <div class="input-group">
                                                            <input type="password" class="form-control form-control-sm password-field" value="" readonly>
                                                            <button class="btn btn-sm btn-outline-secondary toggle-password" type="button" title="Show/Hide">
                                                                <i class="mdi mdi-eye"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-secondary copy-password" type="button" title="Copy">
                                                                <i class="mdi mdi-content-copy"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Regenerate Button -->
                                                    <div class="mb-3">
                                                        <button type="button" class="btn btn-sm btn-warning w-100 regenerate-password" data-vcard-id="{{ $vcard->id }}" data-bs-toggle="modal" data-bs-target="#regenerateConfirmModal{{ $vcard->id }}">
                                                            <i class="mdi mdi-refresh"></i> Regenerate Password
                                                        </button>
                                                    </div>

                                                    <!-- Email Display -->
                                                    <div class="alert alert-light border small mb-0">
                                                        <label class="form-label small fw-semibold mb-2">Will be sent to:</label>
                                                        <div class="text-muted">{{ $vcard->client_email }}</div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-sm btn-success send-credentials" data-vcard-id="{{ $vcard->id }}" data-client-email="{{ $vcard->client_email }}" data-bs-toggle="modal" data-bs-target="#sendConfirmModal{{ $vcard->id }}">
                                                        <i class="mdi mdi-send"></i> Send to Client
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Regenerate Password Confirmation Modal -->
                                    <div class="modal fade" id="regenerateConfirmModal{{ $vcard->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-warning text-dark">
                                                    <h6 class="modal-title">
                                                        <i class="mdi mdi-alert-circle"></i> Regenerate Password
                                                    </h6>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="mb-2">Are you sure you want to regenerate the password?</p>
                                                    <div class="alert alert-warning small mb-0">
                                                        <i class="mdi mdi-information"></i> The old password will no longer work after regeneration.
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-sm btn-warning confirm-regenerate" data-vcard-id="{{ $vcard->id }}" data-parent-modal="shareModal{{ $vcard->id }}">
                                                        <i class="mdi mdi-refresh"></i> Yes, Regenerate
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Send Credentials Confirmation Modal -->
                                    <div class="modal fade" id="sendConfirmModal{{ $vcard->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-success text-white">
                                                    <h6 class="modal-title">
                                                        <i class="mdi mdi-send"></i> Send Credentials
                                                    </h6>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="mb-2">Send login credentials to:</p>
                                                    <div class="alert alert-info small mb-0">
                                                        <strong>{{ $vcard->client_name }}</strong><br>
                                                        <span class="text-muted">{{ $vcard->client_email }}</span>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="button" class="btn btn-sm btn-success confirm-send" data-vcard-id="{{ $vcard->id }}" data-parent-modal="shareModal{{ $vcard->id }}">
                                                        <i class="mdi mdi-send"></i> Yes, Send
                                                    </button>
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

        @if (!$vcards->isEmpty())
            <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center flex-wrap gap-2 px-4 py-3">
                <small class="text-muted">Showing {{ $vcards->firstItem() }} to {{ $vcards->lastItem() }} of {{ $vcards->total() }} results</small>
                <div>
                    {{ $vcards->links('pagination::bootstrap-5') }}
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

        .badge {
            font-weight: 500;
        }

        .btn-sm {
            padding: 0.35rem 0.65rem;
            font-size: 0.875rem;
        }

        .copy-subdomain-btn:hover {
            color: #4f46e5 !important;
        }
    </style>

    <script>
        // Initialize Bootstrap tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Add event listeners to share buttons
            document.querySelectorAll('.share-btn').forEach(button => {
                button.addEventListener('click', handleShareClick);
            });

            // Add event listeners to confirm regenerate buttons (from modal)
            document.querySelectorAll('.confirm-regenerate').forEach(button => {
                button.addEventListener('click', handleConfirmRegeneratePassword);
            });

            // Add event listeners to confirm send buttons (from modal)
            document.querySelectorAll('.confirm-send').forEach(button => {
                button.addEventListener('click', handleConfirmSendCredentials);
            });
        });

        // Use event delegation for dynamic elements
        document.addEventListener('click', function(event) {
            // Toggle password visibility
            if (event.target.closest('.toggle-password')) {
                togglePasswordVisibility(event);
            }
            
            // Copy username
            if (event.target.closest('.copy-username')) {
                copyToClipboard(event);
            }
            
            // Copy password
            if (event.target.closest('.copy-password')) {
                copyToClipboard(event);
            }
        });

        async function handleShareClick(event) {
            const vcardId = event.currentTarget.dataset.vcardId;
            const modal = new bootstrap.Modal(document.getElementById(`shareModal${vcardId}`));
            
            try {
                const response = await fetch(`/admin/vcards/${vcardId}/share`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                        'Accept': 'application/json'
                    }
                });
                
                const text = await response.text();
                let data;
                
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error('JSON Parse Error:', text);
                    alert('Server Error: Invalid response. Check browser console.');
                    return;
                }
                
                if (!response.ok) {
                    alert('Error: ' + (data.error || data.message || 'Unknown error'));
                    return;
                }
                
                // Find the modal for this vcard and populate it with credentials
                const shareModal = document.getElementById(`shareModal${vcardId}`);
                const usernameField = shareModal.querySelector('.username-field');
                const passwordField = shareModal.querySelector('.password-field');
                const toggleIcon = shareModal.querySelector('.toggle-password i');
                
                usernameField.value = data.username;
                passwordField.value = data.password; // Show actual password
                passwordField.type = 'text'; // Show password field as text
                
                // Update toggle icon to show "eye-off" initially
                toggleIcon.classList.remove('mdi-eye-off');
                toggleIcon.classList.add('mdi-eye');
                
                // Store the password in a data attribute for later use
                shareModal.dataset.currentPassword = data.password;
                
                modal.show();
            } catch (error) {
                alert('Error loading credentials: ' + error.message);
                console.error('Fetch Error:', error);
            }
        }

        function togglePasswordVisibility(event) {
            event.preventDefault();
            const button = event.target.closest('.toggle-password');
            const shareModal = button.closest('.modal');
            const passwordField = shareModal.querySelector('.password-field');
            const icon = button.querySelector('i');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('mdi-eye');
                icon.classList.add('mdi-eye-off');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('mdi-eye-off');
                icon.classList.add('mdi-eye');
            }
        }

        function copyToClipboard(event) {
            event.preventDefault();
            const button = event.target.closest('.copy-username, .copy-password');
            const shareModal = button.closest('.modal');
            
            let text;
            if (button.classList.contains('copy-username')) {
                text = shareModal.querySelector('.username-field').value;
            } else if (button.classList.contains('copy-password')) {
                const passwordField = shareModal.querySelector('.password-field');
                text = passwordField.value;
            }
            
            if (!text || text.trim() === '') {
                alert('No value to copy');
                return;
            }
            
            navigator.clipboard.writeText(text).then(() => {
                const originalHtml = button.innerHTML;
                button.innerHTML = '<i class="mdi mdi-check"></i>';
                setTimeout(() => {
                    button.innerHTML = originalHtml;
                }, 2000);
            });
        }

        async function handleConfirmRegeneratePassword(event) {
            const button = event.currentTarget;
            const vcardId = button.dataset.vcardId;
            const parentModalId = button.dataset.parentModal;
            const confirmModal = bootstrap.Modal.getInstance(button.closest('.modal'));
            
            try {
                button.disabled = true;
                button.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Regenerating...';
                
                const response = await fetch(`/admin/vcards/${vcardId}/regenerate-password`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                });
                
                const text = await response.text();
                let data;
                
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error('JSON Parse Error:', text);
                    alert('Server Error: Invalid response. Check browser console.');
                    throw e;
                }
                
                if (!response.ok) {
                    throw new Error(data.error || data.message || 'Failed to regenerate password');
                }
                
                const shareModal = document.getElementById(parentModalId);
                const passwordField = shareModal.querySelector('.password-field');
                const icon = shareModal.querySelector('.toggle-password i');
                
                // Show the new password
                passwordField.type = 'text';
                passwordField.value = data.password;
                icon.classList.remove('mdi-eye-off');
                icon.classList.add('mdi-eye');
                
                shareModal.dataset.currentPassword = data.password;
                
                // Close confirmation modal
                confirmModal.hide();
                
                // Show success message
                const alert = document.createElement('div');
                alert.className = 'alert alert-success alert-dismissible fade show';
                alert.innerHTML = '<i class="mdi mdi-check-circle"></i> Password regenerated successfully!<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                const alertContainer = shareModal.querySelector('.modal-body');
                const existingAlert = alertContainer.querySelector('.alert-info');
                if (existingAlert) {
                    alertContainer.insertBefore(alert, existingAlert);
                } else {
                    alertContainer.insertBefore(alert, alertContainer.firstChild);
                }
                setTimeout(() => alert.remove(), 3000);
            } catch (error) {
                alert('Error: ' + error.message);
                console.error('Error:', error);
            } finally {
                button.disabled = false;
                button.innerHTML = '<i class="mdi mdi-refresh"></i> Yes, Regenerate';
            }
        }

        async function handleConfirmSendCredentials(event) {
            const button = event.currentTarget;
            const vcardId = button.dataset.vcardId;
            const parentModalId = button.dataset.parentModal;
            const confirmModal = bootstrap.Modal.getInstance(button.closest('.modal'));
            
            const shareModal = document.getElementById(parentModalId);
            const passwordField = shareModal.querySelector('.password-field');
            const password = passwordField.value;
            
            try {
                button.disabled = true;
                button.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Sending...';
                
                const response = await fetch(`/admin/vcards/${vcardId}/send-credentials`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ password: password })
                });
                
                const text = await response.text();
                let data;
                
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error('JSON Parse Error:', text);
                    alert('Server Error: Invalid response. Check browser console.');
                    throw e;
                }
                
                if (!response.ok) {
                    throw new Error(data.error || data.message || 'Failed to send credentials');
                }
                
                // Close confirmation modal
                confirmModal.hide();
                
                // Show success message
                const alert = document.createElement('div');
                alert.className = 'alert alert-success alert-dismissible fade show';
                alert.innerHTML = '<i class="mdi mdi-check-circle"></i> Credentials sent successfully!<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                shareModal.querySelector('.modal-body').insertBefore(alert, shareModal.querySelector('.alert-info'));
                setTimeout(() => {
                    alert.remove();
                    bootstrap.Modal.getInstance(shareModal).hide();
                }, 2000);
            } catch (error) {
                alert('Error: ' + error.message);
                console.error('Error:', error);
            } finally {
                button.disabled = false;
                button.innerHTML = '<i class="mdi mdi-send"></i> Yes, Send';
            }
        }

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
