<div>
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">vCard Previews</h5>
            <button type="button" class="btn btn-sm btn-primary" wire:click.prevent="openModal">
                <i class="mdi mdi-plus"></i> Add vCard Preview
            </button>
        </div>

        <div class="card border mb-4">
            <div class="card-body">
                <h6 class="fw-semibold mb-3">Section Text</h6>
                <form wire:submit.prevent="saveSection">
                    <div class="row g-3">
                        <div class="col-12 col-lg-5">
                            <label for="sectionTitle" class="form-label">Title</label>
                            <input
                                id="sectionTitle"
                                type="text"
                                class="form-control @error('sectionTitle') is-invalid @enderror"
                                wire:model="sectionTitle"
                                placeholder="vCard Previews"
                            >
                            @error('sectionTitle')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-lg-7">
                            <label for="sectionSubtitle" class="form-label">Subtitle</label>
                            <input
                                id="sectionSubtitle"
                                type="text"
                                class="form-control @error('sectionSubtitle') is-invalid @enderror"
                                wire:model="sectionSubtitle"
                                placeholder="Explore multiple vCard styles from the CMS. Each preview opens the exact HTML file you uploaded."
                            >
                            @error('sectionSubtitle')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-sm btn-success" wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                <i class="mdi mdi-content-save"></i> Save Section
                            </span>
                            <span wire:loading>
                                <span class="spinner-border spinner-border-sm me-2"></span>
                                Saving...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- vCard Previews List -->
        @if (empty($vcards))
            <div class="alert alert-info" role="alert">
                <i class="mdi mdi-information"></i> No vCard previews added yet. Click "Add vCard Preview" to get started.
            </div>
        @else
            <div class="row">
                @foreach ($vcards as $index => $vcard)
                    <div class="col-md-6 col-lg-4 mb-4" wire:key="vcard-{{ $index }}">
                        <div class="card h-100 border" style="border: 1px solid #dee2e6;">
                            <div class="card-body d-flex flex-column">
                                <!-- Preview File Display -->
                                @if ($vcard['preview_file'])
                                    <div class="mb-3" style="background-color: #f8f9fa; border-radius: 8px; height: 250px; overflow: hidden; margin-bottom: 1rem;">
                                        <iframe src="{{ $vcard['preview_file'] }}" style="width: 100%; height: 100%; border: none;"></iframe>
                                    </div>
                                @else
                                    <div class="mb-3" style="background-color: #f8f9fa; border-radius: 8px; height: 250px; display: flex; align-items: center; justify-content: center; color: #999;">
                                        <i class="mdi mdi-file-document mdi-48px"></i>
                                    </div>
                                @endif

                                <!-- Title and Category -->
                                <div class="flex-grow-1">
                                    <h6 class="card-title mb-1">{{ $vcard['title'] ?? 'Untitled' }}</h6>
                                    <p class="card-text text-muted small mb-3">
                                        <span class="badge bg-light text-dark">{{ $vcard['category'] ?? 'No Category' }}</span>
                                    </p>
                                </div>

                                <!-- Edit, View and Delete Buttons -->
                                <div class="d-flex flex-column gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary" wire:click.prevent="editVcard({{ $index }})">
                                        <i class="mdi mdi-pencil"></i> Edit
                                    </button>
                                    @if ($vcard['preview_file'])
                                        <button type="button" class="btn btn-sm btn-outline-info" wire:click.prevent="openPreview({{ $index }})">
                                            <i class="mdi mdi-eye"></i> Preview
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-outline-danger" wire:click.prevent="deleteVcard({{ $index }})">
                                        <i class="mdi mdi-delete"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>


<!-- Modal Backdrop -->
@if ($showModal || $showPreviewModal)
    <div class="modal-backdrop fade show" style="display: block;"></div>
@endif

<!-- Modal for Add/Edit vCard Preview -->
<div class="modal fade @if($showModal) show @endif" style="@if($showModal) display: block; @endif" tabindex="-1" role="dialog" aria-hidden="@if(!$showModal) true @else false @endif">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-semibold">
                    <i class="mdi mdi-file-document"></i>
                    @if ($editingIndex !== null)
                        Edit vCard Preview
                    @else
                        Add New vCard Preview
                    @endif
                </h5>
                <button type="button" class="btn-close" wire:click.prevent="closeModal" aria-label="Close"></button>
            </div>

            <form wire:submit="saveVcard">
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="modalTitle" class="form-label fw-semibold">
                            Title <span class="text-danger">*</span>
                        </label>
                        <input 
                            wire:model="modalTitle" 
                            type="text" 
                            id="modalTitle" 
                            class="form-control @error('modalTitle') is-invalid @enderror" 
                            placeholder="e.g., Alex Morgan"
                            autofocus
                        >
                        @error('modalTitle')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="modalCategory" class="form-label fw-semibold">
                            Category <span class="text-danger">*</span>
                        </label>
                        <input 
                            wire:model="modalCategory" 
                            type="text" 
                            id="modalCategory" 
                            class="form-control @error('modalCategory') is-invalid @enderror" 
                            placeholder="e.g., Designer, Developer, Business"
                        >
                        @error('modalCategory')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-0">
                        <label for="modalPreviewFile" class="form-label fw-semibold">
                            Preview File (HTML)
                        </label>
                        <div class="input-group mb-2">
                            <input 
                                wire:model="modalPreviewFile" 
                                type="file" 
                                id="modalPreviewFile" 
                                class="form-control" 
                                accept=".html,.htm"
                            >
                            <span class="input-group-text">
                                <i class="mdi mdi-file-document"></i>
                            </span>
                        </div>
                        <small class="text-muted d-block mb-2">Upload an HTML file for the vCard preview</small>
                        
                        @if ($existingPreviewFile)
                            <div class="alert alert-success alert-sm py-2 px-3 mb-0">
                                <small>
                                    <i class="mdi mdi-check-circle"></i> File already uploaded: 
                                    <a href="{{ $existingPreviewFile }}" target="_blank" class="text-success fw-semibold">View</a>
                                </small>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="modal-footer border-top gap-2">
                    <button type="button" class="btn btn-secondary" wire:click.prevent="closeModal">
                        <i class="mdi mdi-close"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove>
                            <i class="mdi mdi-content-save"></i> Save vCard Preview
                        </span>
                        <span wire:loading>
                            <span class="spinner-border spinner-border-sm me-2"></span>
                            Saving...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Preview Modal (Mobile Phone View) -->
<div class="modal fade @if($showPreviewModal) show @endif" style="@if($showPreviewModal) display: block; @endif" tabindex="-1" role="dialog" aria-hidden="@if(!$showPreviewModal) true @else false @endif">
    <div class="modal-dialog" style="max-width: 420px;" role="document">
        <div class="modal-content border-0 rounded-4" style="height: 90vh; display: flex; flex-direction: column;">
            <!-- Phone Header -->
            <div class="modal-header border-bottom" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <h6 class="modal-title fw-semibold" style="color: white; margin: 0;">
                    <i class="mdi mdi-cellphone"></i> Mobile Preview
                </h6>
                <button type="button" class="btn-close btn-close-white" wire:click.prevent="closePreview()" aria-label="Close"></button>
            </div>

            <!-- Phone Screen (iframe) -->
            <div class="modal-body p-0" style="flex: 1; overflow: hidden; display: flex; align-items: center; justify-content: center; background-color: #000;">
                <div style="width: 100%; height: 100%; background: white; overflow: auto;">
                    @if ($previewFile)
                        <iframe 
                            src="{{ $previewFile }}" 
                            style="width: 100%; height: 100%; border: none;"
                            title="vCard Preview"
                        ></iframe>
                    @endif
                </div>
            </div>

            <!-- Phone Footer -->
            <div class="modal-footer border-top" style="background: #f8f9fa;">
                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click.prevent="closePreview()">
                    <i class="mdi mdi-close"></i> Close
                </button>
                @if ($previewFile)
                    <a href="{{ $previewFile }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="mdi mdi-open-in-new"></i> Open in New Tab
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

</div>
