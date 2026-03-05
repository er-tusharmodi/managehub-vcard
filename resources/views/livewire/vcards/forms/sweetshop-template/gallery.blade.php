{{-- sweetshop-template/gallery.blade.php — [{image: "url"}] --}}

<div class="col-12 mb-2">
    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom mb-2">
        <span class="fw-semibold text-uppercase text-muted" style="font-size:.7rem;letter-spacing:.07em;">
            <i class="mdi mdi-image-multiple-outline me-1"></i>Gallery
            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ count($form) }}</span>
        </span>
        <button type="button" class="btn btn-sm btn-primary px-3"
                wire:click="addRowAndSave('', ['image'])">
            <i class="mdi mdi-plus me-1"></i>Add Image
        </button>
    </div>
</div>

@if(!empty($form))
<div class="col-12">
    <div class="row g-3">
        @foreach($form as $gi => $gItem)
        @php $imgUrl = is_array($gItem) ? ($gItem['image'] ?? '') : $gItem; @endphp
        <div class="col-6 col-md-4 col-lg-3" wire:key="ss-gal-{{ $gi }}">
            <div class="border rounded-3 overflow-hidden h-100 d-flex flex-column"
                 style="background:#fff;">

                {{-- Image preview --}}
                <div style="height:120px;background:#f1f5f9;position:relative;overflow:hidden;">
                    @if(!empty($imgUrl))
                        <img src="{{ $imgUrl }}" alt="Gallery {{ $gi + 1 }}"
                             style="width:100%;height:100%;object-fit:cover;">
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <i class="mdi mdi-image-outline text-muted" style="font-size:2rem;"></i>
                        </div>
                    @endif
                    {{-- Index badge --}}
                    <span class="position-absolute top-0 start-0 m-1 badge bg-dark bg-opacity-50"
                          style="font-size:.65rem;">#{{ $gi + 1 }}</span>
                </div>

                {{-- URL input + upload --}}
                <div class="p-2 flex-grow-1 d-flex flex-column gap-1">
                    <input type="url" class="form-control form-control-sm"
                           wire:model="form.{{ $gi }}.image"
                           placeholder="https://…">

                    <div wire:loading wire:target="uploads.{{ $gi }}.image" class="text-center py-1">
                        <span class="spinner-border spinner-border-sm text-primary"></span>
                        <small class="text-primary ms-1">Uploading…</small>
                    </div>
                    <input type="file" class="form-control form-control-sm"
                           wire:model.live="uploads.{{ $gi }}.image"
                           accept="image/*"
                           style="font-size:.75rem;">
                </div>

                {{-- Actions --}}
                <div class="border-top d-flex">
                    <button type="button" class="btn btn-sm btn-light flex-fill border-0 rounded-0 py-1"
                            wire:click="moveRow('', {{ $gi }}, -1)"
                            {{ $gi === 0 ? 'disabled' : '' }} title="Move Left">
                        <i class="mdi mdi-arrow-left" style="font-size:13px;"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-light flex-fill border-0 rounded-0 border-start border-end py-1"
                            wire:click="moveRow('', {{ $gi }}, 1)"
                            {{ $gi === count($form) - 1 ? 'disabled' : '' }} title="Move Right">
                        <i class="mdi mdi-arrow-right" style="font-size:13px;"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger flex-fill border-0 rounded-0 py-1"
                            wire:click="removeRowWithConfirm({{ $gi }}, '')"
                            wire:confirm="Remove this gallery image?"
                            title="Delete">
                        <i class="mdi mdi-delete-outline" style="font-size:13px;"></i>
                    </button>
                </div>

            </div>
        </div>
        @endforeach
    </div>
</div>
@else
<div class="col-12">
    <div class="text-center py-5 rounded-3"
         style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);border:2px dashed #cbd5e1;">
        <i class="mdi mdi-image-multiple-outline fs-1 text-muted mb-2 d-block"></i>
        <p class="fw-semibold text-muted mb-2 small">No gallery images yet</p>
        <button type="button" class="btn btn-sm btn-primary"
                wire:click="addRowAndSave('', ['image'])">
            <i class="mdi mdi-plus me-1"></i>Add First Image
        </button>
    </div>
</div>
@endif
