@extends('admin.layouts.app')

@section('title', 'Create vCard')

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Create vCard</h4>
            <p class="text-muted small mb-0">Create a new vCard and optionally send login credentials to the client</p>
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
                @if (session('credentials_sent'))
                    <p class="mb-2"><i class="mdi mdi-check me-1" style="color: #28a745;"></i> Username and password sent to client email</p>
                @else
                    <p class="mb-2"><i class="mdi mdi-information-outline me-1" style="color: #6c757d;"></i> Credentials email was not sent</p>
                @endif
                {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light border-bottom px-4 py-0">
            <ul class="nav nav-tabs card-header-tabs" id="vcardCreateTabs">
                <li class="nav-item">
                    <a class="nav-link @if(!$errors->hasAny(['head_script','footer_script'])) active @endif py-3" data-bs-toggle="tab" href="#tab-details">
                        <i class="mdi mdi-account-details-outline me-1"></i>Details
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if($errors->hasAny(['head_script','footer_script'])) active @endif py-3" data-bs-toggle="tab" href="#tab-scripts">
                        <i class="mdi mdi-code-tags me-1"></i>Scripts
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.vcards.store') }}" onsubmit="handleSubmit(event)">
                @csrf
                <div class="tab-content">
                    {{-- Details Tab --}}
                    <div class="tab-pane fade @if(!$errors->hasAny(['head_script','footer_script'])) show active @endif" id="tab-details">
                        <div class="row g-3 pt-2">
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
                                <label class="form-label">Subdomain <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text"
                                           name="subdomain"
                                           class="form-control @error('subdomain') is-invalid @enderror"
                                           value="{{ old('subdomain') }}"
                                           placeholder="client-vcard"
                                           pattern="[a-z0-9][a-z0-9-]*[a-z0-9]"
                                           title="Only lowercase letters, numbers, and hyphens allowed"
                                           required>
                                    <span class="input-group-text">.{{ $baseDomain }}</span>
                                </div>
                                @error('subdomain')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Choose a unique subdomain (e.g., johndoe, my-shop, etc.)</small>
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
                                <input type="date" name="subscription_expires_at" class="form-control" value="{{ old('subscription_expires_at') }}" min="{{ date('Y-m-d') }}">
                                @error('subscription_expires_at')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Send client credentials</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="send_credentials" name="send_credentials" value="1" @if(old('send_credentials')) checked @endif>
                                    <label class="form-check-label" for="send_credentials">Email username and password</label>
                                </div>
                                <div class="small text-muted mt-1">Disabled by default. Enable to send credentials after create.</div>
                            </div>
                        </div>
                    </div>

                    {{-- Scripts Tab --}}
                    <div class="tab-pane fade @if($errors->hasAny(['head_script','footer_script'])) show active @endif" id="tab-scripts">
                        <div class="row g-3 pt-2">
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="mdi mdi-code-tags me-1 text-primary"></i>Head Script
                                </label>
                                <textarea name="head_script" class="form-control font-monospace" rows="8"
                                          placeholder="Paste custom &lt;script&gt; or &lt;style&gt; tags to inject inside &lt;head&gt;">{{ old('head_script') }}</textarea>
                                <div class="small text-muted mt-1">Injected just before <code>&lt;/head&gt;</code> on every page load.</div>
                                @error('head_script')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="mdi mdi-code-tags me-1 text-primary"></i>Footer Script
                                </label>
                                <textarea name="footer_script" class="form-control font-monospace" rows="8"
                                          placeholder="Paste custom &lt;script&gt; tags to inject before &lt;/body&gt;">{{ old('footer_script') }}</textarea>
                                <div class="small text-muted mt-1">Injected just before <code>&lt;/body&gt;</code> on every page load.</div>
                                @error('footer_script')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 border-top pt-3">
                    <button type="submit" id="submitBtn" class="btn btn-primary">
                        <i class="mdi mdi-plus-circle me-2"></i>Create vCard
                    </button>
                    <small class="text-muted d-block mt-2">Credentials email is sent only when the toggle is enabled.</small>
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
