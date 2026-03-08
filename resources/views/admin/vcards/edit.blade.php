@extends('admin.layouts.app')

@section('title', 'Edit vCard')

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Edit vCard</h4>
        </div>
        <div class="text-end">
            <a href="{{ route('admin.vcards.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-light border-bottom px-4 py-0">
            <ul class="nav nav-tabs card-header-tabs" id="vcardEditTabs">
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
            <form method="POST" action="{{ route('admin.vcards.update', $vcard->id) }}">
                @csrf
                @method('PUT')
                <div class="tab-content">
                    {{-- Details Tab --}}
                    <div class="tab-pane fade @if(!$errors->hasAny(['head_script','footer_script'])) show active @endif" id="tab-details">
                        <div class="row g-3 pt-2">
                            <div class="col-md-6">
                                <label class="form-label">Client Name</label>
                                <input type="text" name="client_name" class="form-control" value="{{ old('client_name', $vcard->client_name) }}" required>
                                @error('client_name')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Client Email</label>
                                <input type="email" name="client_email" class="form-control" value="{{ old('client_email', $vcard->client_email) }}" required>
                                @error('client_email')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Client Phone</label>
                                <input type="text" name="client_phone" class="form-control" value="{{ old('client_phone', $vcard->client_phone) }}">
                                @error('client_phone')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Client Address</label>
                                <input type="text" name="client_address" class="form-control" value="{{ old('client_address', $vcard->client_address) }}">
                                @error('client_address')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Subdomain (Read-only)</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $vcard->subdomain }}" disabled>
                                    <span class="input-group-text">.{{ $baseDomain }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Template (Read-only)</label>
                                <input type="text" class="form-control" value="{{ $vcard->template_key }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Subscription Status</label>
                                <select name="subscription_status" class="form-select" required>
                                    <option value="active" @if(old('subscription_status', $vcard->subscription_status) === 'active') selected @endif>Active</option>
                                    <option value="inactive" @if(old('subscription_status', $vcard->subscription_status) === 'inactive') selected @endif>Inactive</option>
                                </select>
                                @error('subscription_status')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Subscription Expiry</label>
                                <input type="date" name="subscription_expires_at" class="form-control" value="{{ old('subscription_expires_at', $vcard->subscription_expires_at?->format('Y-m-d')) }}">
                                @error('subscription_expires_at')<div class="text-danger small">{{ $message }}</div>@enderror
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
                                          placeholder="Paste custom &lt;script&gt; or &lt;style&gt; tags to inject inside &lt;head&gt;">{{ old('head_script', $vcard->head_script) }}</textarea>
                                <div class="small text-muted mt-1">Injected just before <code>&lt;/head&gt;</code> on every page load.</div>
                                @error('head_script')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="mdi mdi-code-tags me-1 text-primary"></i>Footer Script
                                </label>
                                <textarea name="footer_script" class="form-control font-monospace" rows="8"
                                          placeholder="Paste custom &lt;script&gt; tags to inject before &lt;/body&gt;">{{ old('footer_script', $vcard->footer_script) }}</textarea>
                                <div class="small text-muted mt-1">Injected just before <code>&lt;/body&gt;</code> on every page load.</div>
                                @error('footer_script')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 border-top pt-3">
                    <button type="submit" class="btn btn-primary">Update vCard</button>
                </div>
            </form>
        </div>
    </div>
@endsection
