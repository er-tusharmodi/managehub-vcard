{{--
 | _shared/gallery.blade.php
 | Gallery image list — handles both:
 |   1. List of plain URL strings  (electronics, bookshop gallery.images)
 |   2. List of dicts with 'image' key  (sweetshop {image:url})
 |   3. List of dicts with 'product_image' key  (minimart)
 |   4. List of dicts with 'caption' + 'image' keys  (restaurant)
 |
 | Available: $form (array — the gallery list itself)
--}}

@php
    $firstItem   = $form[0] ?? null;
    $isScalar    = $firstItem === null || is_string($firstItem);
    $imageKey    = null;
    $hasCaption  = false;

    if (!$isScalar && is_array($firstItem)) {
        if (array_key_exists('product_image', $firstItem))  { $imageKey = 'product_image'; }
        elseif (array_key_exists('image', $firstItem))      { $imageKey = 'image'; }
        $hasCaption = array_key_exists('caption', $firstItem);
    }
@endphp

<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-image-multiple-outline me-1"></i>Gallery Images
    </h6>
    <p class="text-muted" style="font-size:.82rem;">{{ count($form) }} image{{ count($form) !== 1 ? 's' : '' }}</p>
</div>

@forelse($form as $gi => $gItem)
    <div class="col-12 mb-3" wire:key="gallery-{{ $gi }}">
        <div class="border rounded-3 p-3" style="background:#f8fafc;">
            <div class="d-flex align-items-start gap-3">
                {{-- Drag handle + index --}}
                <div class="d-flex flex-column align-items-center gap-1 flex-shrink-0 pt-1">
                    <span class="text-muted fw-semibold" style="font-size:.8rem;">#{{ $gi + 1 }}</span>
                    <button type="button" class="btn btn-sm btn-light p-0" style="width:28px;height:28px;"
                            wire:click="moveRow('', {{ $gi }}, -1)" {{ $gi === 0 ? 'disabled' : '' }}
                            title="Move Up">
                        <i class="mdi mdi-arrow-up" style="font-size:13px;"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-light p-0" style="width:28px;height:28px;"
                            wire:click="moveRow('', {{ $gi }}, 1)" {{ $gi === count($form) - 1 ? 'disabled' : '' }}
                            title="Move Down">
                        <i class="mdi mdi-arrow-down" style="font-size:13px;"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger p-0" style="width:28px;height:28px;"
                            wire:click="confirmRemoveRow('', {{ $gi }})" title="Remove">
                        <i class="mdi mdi-delete" style="font-size:13px;"></i>
                    </button>
                </div>

                {{-- Image Preview + Input --}}
                <div class="flex-grow-1">
                    @if($isScalar)
                        {{-- Simple URL string --}}
                        @php $imgUrl = $gItem; @endphp
                        @if(!empty($imgUrl))
                            <div class="mb-2">
                                <img src="{{ $imgUrl }}" alt="Gallery {{ $gi + 1 }}"
                                     style="max-height:100px;max-width:180px;object-fit:cover;border-radius:6px;border:1px solid #e2e8f0;">
                            </div>
                        @endif
                        <div class="mb-1">
                            <label class="form-label mb-1" style="font-size:.8rem;">Image URL</label>
                            <input type="url"
                                   class="form-control form-control-sm @error('form.' . $gi) is-invalid @enderror"
                                   wire:model="form.{{ $gi }}"
                                   placeholder="https://…">
                            @error('form.' . $gi) <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="form-label mb-1" style="font-size:.8rem;">Or upload new image</label>
                            <div wire:loading wire:target="uploads.{{ $gi }}" class="mb-1">
                                <span class="spinner-border spinner-border-sm text-primary"></span>
                                <small class="text-primary ms-1">Uploading…</small>
                            </div>
                            <input type="file"
                                   class="form-control form-control-sm @error('uploads.' . $gi) is-invalid @enderror"
                                   wire:model.live="uploads.{{ $gi }}"
                                   accept="image/*">
                        </div>
                    @else
                        {{-- Dict item with image key --}}
                        @php $imgUrl2 = $gItem[$imageKey] ?? ''; @endphp
                        @if(!empty($imgUrl2))
                            @php
                                $displayUrl = $imgUrl2;
                                if (preg_match('/url\([\'"]?(.*?)[\'"]?\)/i', $imgUrl2, $m)) {
                                    $displayUrl = $m[1];
                                }
                            @endphp
                            <div class="mb-2">
                                <img src="{{ $displayUrl }}" alt="Gallery {{ $gi + 1 }}"
                                     style="max-height:100px;max-width:180px;object-fit:cover;border-radius:6px;border:1px solid #e2e8f0;">
                            </div>
                        @endif
                        <div class="{{ $hasCaption ? 'row g-2' : '' }}">
                            <div class="{{ $hasCaption ? 'col-lg-8' : '' }}">
                                <label class="form-label mb-1" style="font-size:.8rem;">Image URL</label>
                                <input type="url"
                                       class="form-control form-control-sm @error('form.' . $gi . '.' . $imageKey) is-invalid @enderror"
                                       wire:model="form.{{ $gi }}.{{ $imageKey }}"
                                       placeholder="https://…">
                                @error('form.' . $gi . '.' . $imageKey) <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <div class="mt-1">
                                    <div wire:loading wire:target="uploads.{{ $gi }}.{{ $imageKey }}" class="mb-1">
                                        <span class="spinner-border spinner-border-sm text-primary"></span>
                                        <small class="text-primary ms-1">Uploading…</small>
                                    </div>
                                    <input type="file"
                                           class="form-control form-control-sm"
                                           wire:model.live="uploads.{{ $gi }}.{{ $imageKey }}"
                                           accept="image/*">
                                </div>
                            </div>
                            @if($hasCaption)
                                <div class="col-lg-4">
                                    <label class="form-label mb-1" style="font-size:.8rem;">Caption</label>
                                    <input type="text"
                                           class="form-control form-control-sm @error('form.' . $gi . '.caption') is-invalid @enderror"
                                           wire:model="form.{{ $gi }}.caption"
                                           placeholder="Caption…">
                                    @error('form.' . $gi . '.caption') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="col-12">
        <div class="alert alert-info">
            <i class="mdi mdi-information-outline me-1"></i>No gallery images yet. Use <strong>Add Gallery Image</strong> to add your first image.
        </div>
    </div>
@endforelse
