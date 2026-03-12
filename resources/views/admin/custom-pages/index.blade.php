@extends('admin.layouts.app')

@section('title', 'Custom Pages')

@section('content')
    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column mb-4">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Custom Pages</h4>
            <p class="text-muted small mb-0">Upload an HTML file and serve it on a subdomain</p>
        </div>
        <div class="text-end">
            <a href="{{ route('admin.custom-pages.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus-circle me-2"></i>Upload New Page
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="mdi mdi-check-decagram me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="mdi mdi-alert-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom px-4 py-3">
            <h5 class="mb-0 fw-semibold">
                <i class="mdi mdi-web text-primary me-2"></i>All Custom Pages
            </h5>
        </div>
        <div class="card-body p-0">
            @if ($pages->isEmpty())
                <div class="text-center py-5">
                    <i class="mdi mdi-file-code-outline text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">No custom pages yet. <a href="{{ route('admin.custom-pages.create') }}">Upload one now.</a></p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Title</th>
                                <th>Subdomain</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pages as $page)
                                <tr>
                                    <td class="ps-4 fw-semibold">{{ $page->title ?: $page->subdomain }}</td>
                                    <td>
                                        <a href="https://{{ $page->subdomain }}.{{ $baseDomain }}" target="_blank" class="text-primary text-decoration-none small">
                                            {{ $page->subdomain }}.{{ $baseDomain }}
                                            <i class="mdi mdi-open-in-new ms-1" style="font-size:.75rem;"></i>
                                        </a>
                                    </td>
                                    <td>
                                        @if ($page->status === 'active')
                                            <span class="badge bg-success-subtle text-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">{{ $page->created_at?->diffForHumans() }}</td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('admin.custom-pages.edit', $page->id) }}" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="mdi mdi-pencil me-1"></i>Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.custom-pages.destroy', $page->id) }}" class="d-inline"
                                              onsubmit="return confirm('Delete custom page for subdomain: {{ addslashes($page->subdomain) }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="mdi mdi-delete me-1"></i>Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($pages->hasPages())
                    <div class="px-4 py-3 border-top">
                        {{ $pages->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
