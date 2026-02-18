<div class="card">
    <div class="card-body">
        <form wire:submit="save">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">vCard Previews</h5>
                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="addVcard">
                    <i class="mdi mdi-plus"></i> Add vCard Preview
                </button>
            </div>

            @if (empty($vcards))
                <div class="alert alert-info" role="alert">
                    No vCard previews added yet. Click "Add vCard Preview" to get started.
                </div>
            @else
                @foreach ($vcards as $index => $vcard)
                    <div class="border rounded-3 p-4 mb-4" style="background-color: #f8f9fa;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">
                                <i class="mdi mdi-file-document"></i>
                                vCard Preview {{ $index + 1 }}
                                @if ($vcard['title'])
                                    - <small class="text-muted">{{ $vcard['title'] }}</small>
                                @endif
                            </h6>
                            <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeVcard({{ $index }})">
                                <i class="mdi mdi-delete"></i> Remove
                            </button>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="title_{{ $index }}" class="form-label">Title</label>
                                    <input 
                                        wire:model="vcards.{{ $index }}.title" 
                                        type="text" 
                                        id="title_{{ $index }}" 
                                        class="form-control @error('vcards.' . $index . '.title') is-invalid @enderror" 
                                        placeholder="e.g., Alex Morgan"
                                    >
                                    @error('vcards.' . $index . '.title')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="category_{{ $index }}" class="form-label">Category</label>
                                    <input 
                                        wire:model="vcards.{{ $index }}.category" 
                                        type="text" 
                                        id="category_{{ $index }}" 
                                        class="form-control @error('vcards.' . $index . '.category') is-invalid @enderror" 
                                        placeholder="e.g., Designer, Developer, Business"
                                    >
                                    @error('vcards.' . $index . '.category')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="preview_file_{{ $index }}" class="form-label">Preview File (HTML)</label>
                            <div class="input-group">
                                <input 
                                    wire:model="previewFiles.{{ $index }}" 
                                    type="file" 
                                    id="preview_file_{{ $index }}" 
                                    class="form-control @error('vcards.' . $index . '.preview_file') is-invalid @enderror" 
                                    accept=".html,.htm"
                                >
                                <span class="input-group-text">
                                    <i class="mdi mdi-file-document"></i>
                                </span>
                            </div>
                            <small class="text-muted d-block mt-2">Upload an HTML file for the vCard preview</small>
                            
                            @if ($vcard['preview_file'])
                                <div class="mt-2">
                                    <small class="text-success">
                                        <i class="mdi mdi-check-circle"></i> File uploaded: 
                                        <a href="{{ $vcard['preview_file'] }}" target="_blank" class="text-success">View File</a>
                                    </small>
                                </div>
                            @endif

                            @error('vcards.' . $index . '.preview_file')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                @endforeach
            @endif

            <div class="form-group mt-4">
                <button wire:loading.attr="disabled" type="submit" class="btn btn-primary">
                    <span wire:loading class="spinner-border spinner-border-sm me-2"></span>
                    <span wire:loading.remove>Save vCard Previews</span>
                    <span wire:loading>Saving...</span>
                </button>
                <a href="{{ route('admin.website-cms', $page->slug) }}" class="btn btn-secondary ms-2">Back</a>
            </div>
        </form>
    </div>
</div>
