@extends('admin.layouts.app')

@section('title', 'Create vCard')

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Create vCard</h4>
            <p class="text-muted small mb-0">Create a new vCard and automatically send login credentials to the client</p>
        </div>
        <div class="text-end">
            <a href="{{ route('admin.vcards.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-decagram me-2"></i>
            <strong>vCard Created Successfully!</strong>
            <div class="mt-2 small">
                <p class="mb-2"><i class="mdi mdi-check me-1" style="color: #28a745;"></i> Username and password sent to client email</p>
                {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-bottom px-4 py-3">
            <h5 class="mb-0 fw-semibold"><i class="mdi mdi-plus-circle me-2 text-primary"></i>vCard Details</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.vcards.store') }}" onsubmit="handleSubmit(event)">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Client Name</label>
                        <input type="text" name="client_name" class="form-control" value="{{ old('client_name') }}" required>
                        @error('client_name')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Client Email</label>
                        <input type="email" name="client_email" class="form-control" value="{{ old('client_email') }}" required>
                        @error('client_email')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Client Phone</label>
                        <input type="text" name="client_phone" class="form-control" value="{{ old('client_phone') }}">
                        @error('client_phone')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Client Address</label>
                        <input type="text" name="client_address" class="form-control" value="{{ old('client_address') }}">
                        @error('client_address')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Subdomain</label>
                        <div class="input-group">
                            <input type="text" name="subdomain" class="form-control" value="{{ old('subdomain') }}" placeholder="client-vcard" required>
                            <span class="input-group-text">.{{ $baseDomain }}</span>
                        </div>
                        @error('subdomain')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Template</label>
                        <select name="template_key" class="form-select" required>
                            <option value="" disabled selected>Select template</option>
                            @foreach ($templates as $template)
                                <option value="{{ $template['key'] }}" @if(old('template_key') === $template['key']) selected @endif>
                                    {{ $template['title'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('template_key')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Subscription Status</label>
                        <select name="subscription_status" class="form-select" required>
                            <option value="active" @if(old('subscription_status', 'active') === 'active') selected @endif>Active</option>
                            <option value="inactive" @if(old('subscription_status') === 'inactive') selected @endif>Inactive</option>
                        </select>
                        @error('subscription_status')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Subscription Expiry</label>
                        <input type="date" name="subscription_expires_at" class="form-control" value="{{ old('subscription_expires_at') }}">
                        @error('subscription_expires_at')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" id="submitBtn" class="btn btn-primary">
                        <i class="mdi mdi-plus-circle me-2"></i>Create vCard
                    </button>
                    <small class="text-muted d-block mt-2">✓ Credentials will be automatically sent to the client email</small>
                </div>
            </form>
        </div>
    </div>

    <script>
        function handleSubmit(event) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Creating vCard...';
        }

        // Show toast notification on page load if there's a success message
        document.addEventListener('DOMContentLoaded', function() {
            const successAlert = document.querySelector('.alert-success');
            if (successAlert && window.bootstrap) {
                // Auto-dismiss after 5 seconds
                setTimeout(() => {
                    const alert = bootstrap.Alert.getOrCreateInstance(successAlert);
                    alert.close();
                }, 5000);
            }
        });
    </script>
@endsection
