{{-- electronics-shop-template/gallery.blade.php --}}
{{-- Gallery as a grid of image cards. Data: array of plain URL strings. --}}
<div class="col-12 mb-3">
    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom">
        <span class="fw-semibold text-uppercase text-muted" style="font-size:.7rem;letter-spacing:.07em;">
            <i class="mdi mdi-image-multiple-outline me-1"></i>Gallery
            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ is_array($form) ? count($form) : 0 }}</span>
        </span>
        <button type="button" class="btn btn-sm btn-primary px-3"
                wire:click="addStringAndSave('', '_gallery_slot')">
            <i class="mdi mdi-plus me-1"></i>Add Image Slot
        </button>
    </div>
</div>

@if(is_array($form) && !empty($form))
<div class="col-12">
    <div class="row g-3">
        @foreach($form as $gi => $imgUrl)
        <div class="col-md-4 col-sm-6" wire:key="gallery-{{ $gi }}">
            <div class="border rounded-3 p-2" style="background:#f8fafc;">
                {{-- Preview --}}
                @if(!empty($imgUrl))
                    <div class="mb-2 text-center">
                        <img src="{{ $imgUrl }}"
                             alt="Gallery {{ $gi + 1 }}"
                             style="width:100%;height:130px;object-fit:cover;border-radius:6px;border:1px solid #e2e8f0;">
                    </div>
                @else
                    <div class="mb-2 d-flex align-items-center justify-content-center rounded"
                         style="height:130px;background:#f1f5f9;border:2px dashed #cbd5e1;">
                        <i class="mdi mdi-image-outline fs-1 text-muted"></i>
                    </div>
                @endif

                {{-- Upload --}}
                <div class="mb-2">
                    <div wire:loading wire:target="uploads.{{ $gi }}" class="text-primary small mb-1">
                        <i class="mdi mdi-loading mdi-spin me-1"></i>Uploading…
                    </div>
                    <input type="file"
                           class="form-control form-control-sm"
                           wire:model.live="uploads.{{ $gi }}"
                           accept="image/*">
                </div>

                {{-- Actions --}}
                <div class="d-flex gap-1 align-items-center">
                    <button type="button" class="btn btn-sm btn-outline-secondary p-0 rounded-circle"
                            style="width:26px;height:26px;" title="Move Up"
                            wire:click="moveRow('', {{ $gi }}, -1)"
                            {{ $gi === 0 ? 'disabled' : '' }}>
                        <i class="mdi mdi-arrow-up" style="font-size:12px;"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary p-0 rounded-circle"
                            style="width:26px;height:26px;" title="Move Down"
                            wire:click="moveRow('', {{ $gi }}, 1)"
                            {{ $gi === count($form) - 1 ? 'disabled' : '' }}>
                        <i class="mdi mdi-arrow-down" style="font-size:12px;"></i>
                    </button>
                    <span class="text-muted small ms-1">#{{ $gi + 1 }}</span>
                    <button type="button" class="btn btn-sm btn-outline-danger p-0 rounded-circle ms-auto"
                            style="width:26px;height:26px;" title="Remove"
                            wire:click="removeRowWithConfirm({{ $gi }}, '')"
                            wire:confirm="Remove this image?">
                        <i class="mdi mdi-delete" style="font-size:12px;"></i>
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
        <i class="mdi mdi-image-plus fs-1 text-muted mb-2 d-block"></i>
        <p class="fw-semibold text-muted mb-2 small">No gallery images yet</p>
        <button type="button" class="btn btn-sm btn-primary"
                wire:click="addStringAndSave('', '_gallery_slot')">
            <i class="mdi mdi-plus me-1"></i>Add First Image Slot
        </button>
    </div>
</div>
@endif

<div class="col-12 mt-2">
    <small class="text-muted">
        <i class="mdi mdi-information-outline me-1"></i>Click <strong>Add Image Slot</strong> to create a new slot, then upload an image. Click <strong>Save Changes</strong> after uploading.
    </small>
</div>
