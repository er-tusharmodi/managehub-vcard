@extends('admin.layouts.app')

@section('title', 'Edit Custom Page')

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Edit Custom Page</h4>
            <p class="text-muted small mb-0">
                Live URL:
                <a href="https://{{ $page->subdomain }}.{{ $baseDomain }}" target="_blank" class="text-primary">
                    {{ $page->subdomain }}.{{ $baseDomain }}
                    <i class="mdi mdi-open-in-new" style="font-size:.8rem;"></i>
                </a>
            </p>
        </div>
        <div class="text-end">
            <a href="{{ route('admin.custom-pages.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="mdi mdi-arrow-left me-1"></i>Back
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-decagram me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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
            <form method="POST" action="{{ route('admin.custom-pages.update', $page->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Subdomain</label>
                        <div class="input-group">
                            <input type="text" class="form-control bg-light" value="{{ $page->subdomain }}" disabled>
                            <span class="input-group-text text-muted">.{{ $baseDomain }}</span>
                        </div>
                        <small class="text-muted">Subdomain cannot be changed after creation.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Page Title</label>
                        <input type="text"
                               name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $page->title) }}"
                               placeholder="My Custom Page">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            Replace HTML File
                            <span class="text-muted fw-normal">(optional &mdash; leave blank to keep current)</span>
                        </label>
                        <input type="file"
                               name="html_file"
                               class="form-control @error('html_file') is-invalid @enderror"
                               accept=".html,.htm"
                               id="htmlFileInput">
                        @error('html_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Accepted: .html, .htm &mdash; Max size: 5 MB.</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="active" {{ old('status', $page->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $page->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12" id="newPreviewWrapper" style="display:none;">
                        <label class="form-label fw-semibold">
                            New File Preview <span class="text-muted fw-normal small">(first 2000 characters)</span>
                        </label>
                        <div class="border rounded p-2 bg-light" style="max-height: 220px; overflow-y: auto;">
                            <pre id="newHtmlPreview" class="mb-0 small text-muted" style="white-space: pre-wrap; word-break: break-all;"></pre>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">
                            Current HTML Content
                            <span class="text-muted fw-normal small">(preview)</span>
                        </label>
                        <div class="border rounded p-2 bg-light" style="max-height: 250px; overflow-y: auto;">
                            <pre class="mb-0 small text-muted" style="white-space: pre-wrap; word-break: break-all;">{{ Str::limit(strip_tags($page->html_content ?? '(empty)'), 3000) }}</pre>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save me-2"></i>Save Changes
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
        document.getElementById('newHtmlPreview').textContent = txt.substring(0, 2000) + (txt.length > 2000 ? '\n...(truncated)' : '');
        document.getElementById('newPreviewWrapper').style.display = '';
    };
    reader.readAsText(file);
});
</script>
@endpush
