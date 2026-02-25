@extends('admin.layouts.app')

@section('title', 'vCard Templates')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">vCard Templates</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Templates</li>
                    </ol>
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

    <!-- Templates Grid -->
    <div class="row">
        @forelse($templates as $template)
        <div class="col-xl-3 col-md-4 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-sm rounded bg-soft-primary flex-shrink-0">
                            <i class="mdi mdi-file-code font-size-24 text-primary"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1">{{ ucwords(str_replace('-', ' ', $template['key'])) }}</h5>
                            <p class="text-muted mb-0 font-size-13">
                                {{ $template['vcard_count'] }} vCard{{ $template['vcard_count'] != 1 ? 's' : '' }}
                            </p>
                        </div>
                    </div>

                    <div class="template-preview bg-light rounded mb-3" style="height: 350px; overflow: hidden;">
                        <iframe 
                            src="{{ route('admin.templates.preview', $template['key']) }}" 
                            class="border-0" 
                            style="transform: scale(0.4); transform-origin: top left; width: 250%; height: 250%;"
                            loading="lazy">
                        </iframe>
                    </div>

                    <div class="d-flex gap-2">
                        <div class="dropdown flex-grow-1">
                            <button class="btn btn-primary w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown">
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
                                        <i class="mdi mdi-eye-outline me-2"></i>Preview
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <button 
                            type="button" 
                            class="btn btn-danger" 
                            data-template-key="{{ $template['key'] }}"
                            data-template-name="{{ ucwords(str_replace('-', ' ', $template['key'])) }}"
                            data-vcard-count="{{ $template['vcard_count'] }}"
                            data-can-delete="{{ $template['can_delete'] ? 'true' : 'false' }}"
                            onclick="confirmDelete(this)"
                            @if(!$template['can_delete']) disabled title="Cannot delete - vCards are using this template" @endif>
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </div>
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

@push('scripts')
<script>
let deleteTemplateKey = null;

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

function showToast(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const icon = type === 'success' ? 'mdi-check-circle' : 'mdi-alert-circle';
    
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
</script>
@endpush
@endsection
