@extends('admin.layouts.app')

@section('title', 'Create vCard')

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Create vCard</h4>
        </div>
        <div class="text-end">
            <a href="{{ route('admin.vcards.index') }}" class="btn btn-sm btn-outline-secondary">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.vcards.store') }}">
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
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Create vCard</button>
                </div>
            </form>
        </div>
    </div>
@endsection
