{{-- sweetshop-template/gallery.blade.php --}}
{{-- Photo grid: multi-add via $wire.upload(), delete only, drag/drop sort --}}
{{-- Items: [{image}] --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-image-multiple me-1"></i>Gallery Photos
        <span class="badge bg-secondary-subtle text-secondary ms-1">{{ count($form ?? []) }}</span>
    </h6>
    <small class="text-muted">Drag <i class="mdi mdi-drag-vertical"></i> to reorder · Click <i class="mdi mdi-delete text-danger"></i> to remove.</small>
</div>

{{-- Photo Grid (drag/drop container) --}}
<div class="col-12">
    <div class="row g-2 ss-gallery-grid" data-sort-path="">
        @foreach(($form ?? []) as $gi => $gItem)
        <div class="col-6 col-md-4 col-lg-3 ss-gallery-item" wire:key="ss-gal-{{ $gi }}">
            <div class="position-relative border rounded overflow-hidden bg-light" style="aspect-ratio:1;">
                {{-- Image --}}
                @if(!empty($gItem['image']))
                    <img src="{{ $gItem['image'] }}" alt="Gallery photo {{ $gi + 1 }}"
                         class="w-100 h-100" style="object-fit:cover;">
                @else
                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                        <i class="mdi mdi-image-outline" style="font-size:2rem;"></i>
                    </div>
                @endif
                {{-- Drag handle — full card overlay so any drag gesture works --}}
                <span class="drag-handle position-absolute top-0 start-0 w-100 h-100"
                      style="cursor:grab;z-index:2;"></span>
                {{-- Drag icon indicator (purely visual) --}}
                <span class="position-absolute top-0 start-0 m-1 d-flex align-items-center justify-content-center rounded"
                      style="width:22px;height:22px;background:rgba(0,0,0,.45);z-index:2;pointer-events:none;">
                    <i class="mdi mdi-drag-vertical text-white" style="font-size:14px;"></i>
                </span>
                {{-- Delete button (bottom, above drag overlay) --}}
                <button type="button"
                        class="btn btn-danger btn-sm position-absolute bottom-0 start-0 end-0 rounded-0"
                        style="z-index:4;font-size:.72rem;padding:2px 0;"
                        onclick="event.stopPropagation(); showConfirmToast('Delete this photo?', function(){ window.Livewire.find('{{ $_instance->getId() }}').call('removeRowWithConfirm', {{ $gi }}, '') })">
                    <i class="mdi mdi-delete me-1" style="font-size:12px;"></i>Delete
                </button>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Multi-image add section --}}
<div class="col-12 mt-3"
     x-data="{ uploading: false, progress: 0, done: 0, total: 0 }">
    <div class="border rounded p-3 bg-light">
        <label class="fw-semibold small mb-2 d-block">
            <i class="mdi mdi-plus-circle me-1 text-success"></i>Add New Photos
        </label>

        {{-- Progress bar shown while uploading --}}
        <div x-show="uploading" class="mb-2">
            <div class="d-flex justify-content-between small text-muted mb-1">
                <span>Uploading <span x-text="done"></span> / <span x-text="total"></span>…</span>
                <span x-text="progress + '%'"></span>
            </div>
            <div class="progress" style="height:6px;">
                <div class="progress-bar bg-success" :style="'width:' + progress + '%'"></div>
            </div>
        </div>

        <input type="file" id="ss-gallery-multi-add"
               class="form-control form-control-sm mb-2"
               accept="image/*" multiple
               x-on:change="
                   const files = Array.from($event.target.files);
                   if (!files.length) return;
                   $event.target.value = '';
                   uploading = true;
                   done = 0;
                   total = files.length;
                   progress = 0;

                   function uploadNext(idx) {
                       if (idx >= files.length) {
                           uploading = false;
                           return;
                       }
                       $wire.upload(
                           'galleryUploadFile',
                           files[idx],
                           () => { done = idx + 1; progress = Math.round((done / total) * 100); uploadNext(idx + 1); },
                           () => { done = idx + 1; progress = Math.round((done / total) * 100); uploadNext(idx + 1); },
                           (pct) => {}
                       );
                   }
                   uploadNext(0);
               ">
        <small class="text-muted"><i class="mdi mdi-information-outline me-1"></i>Select one or more images — they upload automatically.</small>
    </div>
</div>
