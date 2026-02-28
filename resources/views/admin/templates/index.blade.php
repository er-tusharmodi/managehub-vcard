@extends('admin.layouts.app')

@section('title', 'vCard Templates')

@push('styles')
<style>
    .template-card {
        cursor: move;
        transition: all 0.3s ease;
    }
    .template-card:hover {
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15);
    }
    .template-card.sortable-ghost {
        opacity: 0.5;
    }
    .template-card.sortable-drag {
        opacity: 0.8;
    }
    /* Grid Container for Drag & Drop */
    .templates-grid-container {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .template-item {
        position: relative;
        flex: 0 0 calc(25% - 0.75rem);
        width: calc(25% - 0.75rem);
        cursor: move;
        cursor: grab;
    }
    
    .template-item:active {
        cursor: grabbing;
    }
    
    @media (max-width: 1200px) {
        .template-item {
            flex: 0 0 calc(33.333% - 0.75rem);
            width: calc(33.333% - 0.75rem);
        }
    }
    
    @media (max-width: 768px) {
        .template-item {
            flex: 0 0 calc(50% - 0.75rem);
            width: calc(50% - 0.75rem);
        }
    }
    
    @media (max-width: 576px) {
        .template-item {
            flex: 0 0 100%;
            width: 100%;
        }
    }
    
    .drag-handle {
        cursor: grab;
        padding: 5px;
        color: #6c757d;
        user-select: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
    }
    .drag-handle:hover {
        color: #0d6efd;
    }
    .drag-handle:active {
        cursor: grabbing;
    }
    
    .template-item {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .template-item:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .sortable-ghost {
        opacity: 0.4;
        background: #f8f9fa;
    }
    
    .sortable-chosen {
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    
    .sortable-drag {
        opacity: 0.8;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }
    
    .editable-field {
        cursor: pointer;
        transition: color 0.2s;
    }
    
    .editable-field:hover {
        color: #0d6efd;
        text-decoration: underline;
    }
    .visibility-badge {
        font-size: 11px;
        padding: 2px 8px;
    }
    .order-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 10;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 12px;
    }
    .editable-field {
        cursor: pointer;
        border-bottom: 1px dashed #dee2e6;
        display: inline-block;
    }
    .editable-field:hover {
        border-bottom-color: #0d6efd;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box p-3 d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="mb-0">vCard Templates</h4>
                    <p class="text-muted mb-0 mt-1">Manage templates and control home page display</p>
                </div>
                <div class="page-title-right">
                    <form method="POST" action="{{ route('admin.templates.sync') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary btn-sm me-2">
                            <i class="mdi mdi-sync"></i> Sync from Filesystem
                        </button>
                    </form>
                    <button type="button" 
                            class="btn btn-outline-success btn-sm"
                            onclick="confirmSyncSections()">
                        <i class="mdi mdi-cog-sync"></i> Sync Sections Config
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="alert alert-info alert-dismissible fade show">
        <i class="mdi mdi-information-outline me-2"></i>
        <strong>Drag & Drop</strong> to reorder templates on home page. <strong>Toggle visibility</strong> to show/hide on frontend.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <!-- Templates Grid -->
    <div id="templates-grid" class="templates-grid-container">
        @forelse($templates as $template)
        <div class="template-item card" data-template-id="{{ $template['id'] }}" data-template-key="{{ $template['key'] }}">
            <div class="order-badge">
                #{{ $loop->iteration }}
            </div>
            
            <div class="card-body">
                <!-- Header with Drag Handle -->
                <div class="d-flex align-items-center mb-3">
                    <div class="drag-handle me-2" title="Drag to reorder">
                        <i class="mdi mdi-drag-vertical font-size-20"></i>
                    </div>
                    <div class="avatar-sm rounded bg-soft-primary flex-shrink-0">
                        <i class="mdi mdi-file-code font-size-24 text-primary"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="card-title mb-1 editable-field" 
                            data-field="display_name"
                            data-template-key="{{ $template['key'] }}"
                                onclick="editField(this)"
                                title="Click to edit">
                                {{ $template['display_name'] ?? ucwords(str_replace('-', ' ', $template['key'])) }}
                            </h5>
                            <small class="text-muted editable-field" 
                                   data-field="category"
                                   data-template-key="{{ $template['key'] }}"
                                   onclick="editField(this)"
                                   title="Click to edit category">
                                {{ $template['category'] ?? 'General' }}
                            </small>
                        </div>
                    </div>

                    <!-- Visibility Toggle & vCard Count -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input visibility-toggle" 
                                   type="checkbox" 
                                   id="visibility-{{ $template['key'] }}"
                                   data-template-key="{{ $template['key'] }}"
                                   {{ ($template['is_visible'] ?? false) ? 'checked' : '' }}
                                   onchange="toggleVisibility(this)">
                            <label class="form-check-label" for="visibility-{{ $template['key'] }}">
                                Show on Home
                            </label>
                        </div>
                        <span class="badge {{ ($template['is_visible'] ?? false) ? 'bg-success' : 'bg-secondary' }} visibility-badge">
                            <i class="mdi {{ ($template['is_visible'] ?? false) ? 'mdi-eye' : 'mdi-eye-off' }}"></i>
                            {{ ($template['is_visible'] ?? false) ? 'Visible' : 'Hidden' }}
                        </span>
                    </div>

                    <p class="text-muted mb-3 font-size-13">
                        <i class="mdi mdi-credit-card-outline me-1"></i>
                        {{ $template['vcard_count'] }} vCard{{ $template['vcard_count'] != 1 ? 's' : '' }}
                    </p>

                    <!-- Preview -->
                    <div class="template-preview bg-light rounded mb-3" style="height: 300px; overflow: hidden; position: relative;">
                        <iframe 
                            src="{{ route('admin.templates.preview', $template['key']) }}" 
                            class="border-0" 
                            style="transform: scale(0.4); transform-origin: top left; width: 250%; height: 250%;"
                            loading="lazy">
                        </iframe>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <div class="dropdown flex-grow-1">
                            <button class="btn btn-primary w-100 dropdown-toggle btn-sm" type="button" data-bs-toggle="dropdown">
                                <i class="mdi mdi-pencil me-1"></i> Edit
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.templates.edit.visual', $template['key']) }}">
                                        <i class="mdi mdi-view-dashboard-outline me-2"></i>Visual Editor
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.templates.edit.code', $template['key']) }}">
                                        <i class="mdi mdi-code-braces me-2"></i>Code Editor
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.templates.preview', $template['key']) }}" target="_blank">
                                        <i class="mdi mdi-eye-outline me-2"></i>Full Preview
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <button 
                            type="button" 
                            class="btn btn-danger btn-sm" 
                            data-template-key="{{ $template['key'] }}"
                            data-template-name="{{ $template['display_name'] ?? ucwords(str_replace('-', ' ', $template['key'])) }}"
                            data-vcard-count="{{ $template['vcard_count'] }}"
                            data-can-delete="{{ $template['can_delete'] ? 'true' : 'false' }}"
                            onclick="confirmDelete(this)"
                            @if(!$template['can_delete']) disabled title="Cannot delete - vCards are using this template" @endif>
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </div>
                </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="mdi mdi-file-code-outline display-4 text-muted"></i>
                    <h5 class="mt-3">No Templates Found</h5>
                    <p class="text-muted">No vCard templates are available in the system.</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Edit Field Modal -->
<div class="modal fade" id="editFieldModal" tabindex="-1" aria-labelledby="editFieldModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFieldModalLabel">Edit Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editFieldForm">
                    <div class="mb-3">
                        <label for="editFieldValue" class="form-label"></label>
                        <input type="text" class="form-control" id="editFieldValue" required>
                        <div class="invalid-feedback">This field is required.</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveFieldEdit()">Save Changes</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- SortableJS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
let deleteTemplateKey = null;

// Initialize Sortable for drag-drop
window.addEventListener('load', function() {
    const grid = document.getElementById('templates-grid');
    
    if (!grid) {
        console.error('Templates grid not found');
        return;
    }
    
    try {
        new Sortable(grid, {
            animation: 200,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onEnd: async function(evt) {
                if (evt.oldIndex !== evt.newIndex) {
                    await updateOrder();
                }
            }
        });
    } catch (error) {
        console.error('Failed to initialize drag & drop:', error);
    }
});

// Update order after drag-drop
async function updateOrder() {
    const templateItems = document.querySelectorAll('.template-item');
    const order = Array.from(templateItems).map((item, index) => ({
        id: item.dataset.templateId,
        position: index
    })).filter(item => item.id);

    if (order.length === 0) {
        showToast('warning', 'No templates to reorder');
        return;
    }

    try {
        const response = await fetch('{{ route("admin.templates.updateOrder") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ order })
        });

        const result = await response.json();

        if (result.success) {
            showToast('success', result.message);
            // Update order badges
            templateItems.forEach((item, index) => {
                const badge = item.querySelector('.order-badge');
                if (badge) badge.textContent = `#${index + 1}`;
            });
        } else {
            throw new Error(result.message || 'Failed to update order');
        }
    } catch (error) {
        showToast('error', error.message);
        window.location.reload();
    }
}

// Toggle visibility
async function toggleVisibility(checkbox) {
    const templateKey = checkbox.dataset.templateKey;
    const isVisible = checkbox.checked;

    try {
        const response = await fetch(`/admin/templates/${templateKey}/toggle-visibility`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });

        const result = await response.json();

        if (result.success) {
            // Update visibility badge
            const card = checkbox.closest('.card');
            const badge = card.querySelector('.visibility-badge');
            if (badge) {
                badge.className = `badge ${result.is_visible ? 'bg-success' : 'bg-secondary'} visibility-badge`;
                badge.innerHTML = `<i class="mdi ${result.is_visible ? 'mdi-eye' : 'mdi-eye-off'}"></i> ${result.is_visible ? 'Visible' : 'Hidden'}`;
            }
            showToast('success', result.message);
        } else {
            throw new Error(result.message || 'Failed to toggle visibility');
        }
    } catch (error) {
        checkbox.checked = !isVisible;
        showToast('error', error.message);
    }
}

// Edit field modal variables
let editFieldData = {
    element: null,
    field: null,
    templateKey: null,
    currentValue: null
};

// Edit field (display name or category)
function editField(element) {
    const field = element.dataset.field;
    const templateKey = element.dataset.templateKey;
    const currentValue = element.textContent.trim();
    const fieldLabel = field === 'display_name' ? 'Display Name' : 'Category';

    // Store data for save operation
    editFieldData = {
        element: element,
        field: field,
        templateKey: templateKey,
        currentValue: currentValue
    };

    // Update modal
    document.getElementById('editFieldModalLabel').textContent = `Edit ${fieldLabel}`;
    document.querySelector('#editFieldModal label').textContent = fieldLabel;
    document.getElementById('editFieldValue').value = currentValue;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('editFieldModal'));
    modal.show();
    
    // Focus input after modal is shown
    setTimeout(() => {
        document.getElementById('editFieldValue').focus();
        document.getElementById('editFieldValue').select();
    }, 300);
}

// Save field edit from modal
async function saveFieldEdit() {
    const newValue = document.getElementById('editFieldValue').value.trim();
    const input = document.getElementById('editFieldValue');
    
    if (newValue === '') {
        input.classList.add('is-invalid');
        return;
    }
    
    input.classList.remove('is-invalid');
    
    if (newValue === editFieldData.currentValue) {
        bootstrap.Modal.getInstance(document.getElementById('editFieldModal')).hide();
        return;
    }

    const fieldLabel = editFieldData.field === 'display_name' ? 'Display Name' : 'Category';

    try {
        const endpoint = editFieldData.field === 'display_name' 
            ? `/admin/templates/${editFieldData.templateKey}/display-name`
            : `/admin/templates/${editFieldData.templateKey}/category`;

        const response = await fetch(endpoint, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ [editFieldData.field]: newValue })
        });

        const result = await response.json();

        if (result.success) {
            editFieldData.element.textContent = result[editFieldData.field];
            showToast('success', result.message);
            bootstrap.Modal.getInstance(document.getElementById('editFieldModal')).hide();
        } else {
            throw new Error(result.message || `Failed to update ${fieldLabel}`);
        }
    } catch (error) {
        showToast('error', error.message);
    }
}

// Handle Enter key in modal
document.addEventListener('DOMContentLoaded', function() {
    const editFieldInput = document.getElementById('editFieldValue');
    if (editFieldInput) {
        editFieldInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                saveFieldEdit();
            }
        });
    }
});

// Confirm delete template
function confirmDelete(button) {
    const templateKey = button.dataset.templateKey;
    const templateName = button.dataset.templateName;
    const vcardCount = parseInt(button.dataset.vcardCount);
    const canDelete = button.dataset.canDelete === 'true';

    if (!canDelete) {
        alert(`Cannot delete "${templateName}" template.\n${vcardCount} vCard(s) are currently using this template.`);
        return;
    }

    deleteTemplateKey = templateKey;
    const message = `Are you sure you want to delete the "${templateName}" template? This action cannot be undone.`;

    if (typeof window.showConfirmToast === 'function') {
        window.showConfirmToast(message, function () {
            performDelete();
        }, templateName);
    } else {
        if (confirm(message)) {
            performDelete();
        }
    }
}

// Perform delete
async function performDelete() {
    if (!deleteTemplateKey) return;

    try {
        const response = await fetch(`/admin/templates/${deleteTemplateKey}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });

        const result = await response.json();

        if (result.success) {
            showToast('success', result.message);
            setTimeout(() => window.location.reload(), 1000);
        } else {
            throw new Error(result.message || 'Failed to delete template');
        }
    } catch (error) {
        showToast('error', error.message);
    }
}

// Show toast notification
function showToast(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 
                       type === 'info' ? 'alert-info' : 
                       type === 'warning' ? 'alert-warning' : 'alert-danger';
    const icon = type === 'success' ? 'mdi-check-circle' : 
                 type === 'info' ? 'mdi-information' : 
                 type === 'warning' ? 'mdi-alert' : 'mdi-alert-circle';
    
    const alert = document.createElement('div');
    alert.className = `alert ${alertClass} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
    alert.style.zIndex = '9999';
    alert.innerHTML = `
        <i class="mdi ${icon} me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(alert);
    
    setTimeout(() => {
        alert.remove();
    }, 5000);
}

// Sync sections confirmation
function confirmSyncSections() {
    const message = 'Sync section configurations to all vCards?';
    const itemName = 'This will update all existing vCard data files with the latest section configuration from their templates.';
    
    // Create custom toast with Confirm button (not Delete)
    var container = document.getElementById('admin-toast-container');
    if (!container) {
        if (confirm(message + '\n\n' + itemName)) {
            performSyncSections();
        }
        return;
    }

    var toastEl = document.createElement('div');
    toastEl.className = 'toast align-items-center border-0 mb-2 fade';
    toastEl.setAttribute('role', 'alert');
    toastEl.setAttribute('aria-live', 'assertive');
    toastEl.setAttribute('aria-atomic', 'true');
    toastEl.style.minWidth = '500px';
    
    toastEl.innerHTML = '' +
        '<div class="toast-body bg-white text-dark border-2 border-primary rounded-3 d-flex align-items-center justify-content-between">' +
            '<div class="d-flex align-items-center gap-3">' +
                '<i class="mdi mdi-sync-circle" style="color: #0d6efd; font-size: 1.8em; flex-shrink: 0;"></i>' +
                '<div>' +
                    '<strong class="d-block">' + message + '</strong>' +
                    '<p class="mb-0 mt-2 small text-muted">' + itemName + '</p>' +
                '</div>' +
            '</div>' +
            '<div class="d-flex gap-2" style="flex-shrink: 0;">' +
                '<button type="button" class="btn btn-sm btn-primary" data-confirm="true">Confirm</button>' +
                '<button type="button" class="btn btn-sm btn-secondary" data-cancel="true">Cancel</button>' +
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
            performSyncSections();
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
    } else {
        toastEl.classList.add('show');
    }
}

// Perform sync sections
async function performSyncSections() {
    try {
        const response = await fetch('{{ route('admin.vcards.syncSections') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });

        const result = await response.json();

        if (result.success) {
            showToast('success', result.message);
            setTimeout(() => window.location.reload(), 1500);
        } else {
            throw new Error(result.message || 'Failed to sync sections');
        }
    } catch (error) {
        showToast('error', error.message);
    }
}
</script>
@endpush
@endsection
