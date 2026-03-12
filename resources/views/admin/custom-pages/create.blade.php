@extends('admin.layouts.app')

@section('title', 'Upload Custom Page')

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Upload Custom Page</h4>
            <p class="text-muted small mb-0">Upload an HTML file to serve it on a subdomain</p>
        </div>
        <div class="text-end">
            <a href="{{ route('admin.custom-pages.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="mdi mdi-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle me-2"></i>
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.custom-pages.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Subdomain <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="text"
                                   name="subdomain"
                                   class="form-control @error('subdomain') is-invalid @enderror"
                                   value="{{ old('subdomain') }}"
                                   placeholder="my-page"
                                   required>
                            <span class="input-group-text text-muted">.{{ $baseDomain }}</span>
                            @error('subdomain')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted">Lowercase letters, numbers, hyphens only. Must be unique.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Page Title</label>
                        <input type="text"
                               name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}"
                               placeholder="My Custom Page">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">HTML File <span class="text-danger">*</span></label>
                        <input type="file"
                               name="html_file"
                               class="form-control @error('html_file') is-invalid @enderror"
                               accept=".html,.htm"
                               id="htmlFileInput"
                               required>
                        @error('html_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Accepted: .html, .htm &mdash; Max size: 5 MB. PHP code will be stripped automatically.</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12" id="htmlPreviewWrapper" style="display:none;">
                        <label class="form-label fw-semibold">
                            File Preview <span class="text-muted fw-normal small">(first 2000 characters)</span>
                        </label>
                        <div class="border rounded p-2 bg-light" style="max-height: 220px; overflow-y: auto;">
                            <pre id="htmlPreview" class="mb-0 small text-muted" style="white-space: pre-wrap; word-break: break-all;"></pre>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-upload me-2"></i>Upload &amp; Save
                    </button>
                    <a href="{{ route('admin.custom-pages.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('htmlFileInput').addEventListener('change', function () {
    var file = this.files[0];
    if (!file) return;
    var reader = new FileReader();
    reader.onload = function (e) {
        var txt = e.target.result;
        document.getElementById('htmlPreview').textContent = txt.substring(0, 2000) + (txt.length > 2000 ? '\n...(truncated)' : '');
        document.getElementById('htmlPreviewWrapper').style.display = '';
    };
    reader.readAsText(file);
});
</script>
@endpush
