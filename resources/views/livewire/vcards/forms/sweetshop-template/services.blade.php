{{-- sweetshop-template/services.blade.php --}}
{{-- Items: [{name, description, image}] — card grid with modal add/edit, drag-drop sort --}}
@php $items = is_array($form) ? array_values($form) : []; @endphp

{{-- ── Header ── --}}
<div class="col-12 mb-2">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
                <i class="mdi mdi-briefcase-outline me-1"></i>Services
                <span class="badge bg-primary-subtle text-primary-emphasis ms-1">{{ count($items) }}</span>
            </h6>
            <small class="text-muted">Drag <i class="mdi mdi-drag-vertical"></i> to reorder · Each service shown as a row in template.</small>
        </div>
        <button type="button" class="btn btn-primary btn-sm px-3"
                wire:click="openItemModal(null, {{ json_encode(['name'=>'','description'=>'','image'=>'']) }})">
            <i class="mdi mdi-plus me-1"></i>Add Service
        </button>
    </div>
</div>

{{-- ── Card grid (sortable) ── --}}
@if(count($items) > 0)
<div class="col-12">
    <div class="row g-2" data-sort-path="">
        @foreach($items as $si => $svc)
        <div class="col-md-4" wire:key="ss-svc-{{ $si }}">
            <div class="card border shadow-sm overflow-hidden" style="border-radius:.75rem;">
                {{-- Image --}}
                @php
                    $svcImg = $svc['image'] ?? '';
                    if ($svcImg && preg_match('/url\([\'"]?(.*?)[\'"]?\)/i', $svcImg, $_m)) { $svcImg = $_m[1]; }
                @endphp
                <div class="position-relative" style="aspect-ratio:4/3;background:#f1f5f9;">
                    @if($svcImg)
                        <img src="{{ $svcImg }}" alt="{{ $svc['name'] ?? '' }}"
                             class="w-100 h-100" style="object-fit:cover;">
                    @else
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                            <i class="mdi mdi-image-outline" style="font-size:2rem;"></i>
                        </div>
                    @endif
                    {{-- Drag handle --}}
                    <span class="drag-handle position-absolute top-0 start-0 m-1 d-flex align-items-center justify-content-center rounded"
                          style="width:24px;height:24px;background:rgba(0,0,0,.45);cursor:grab;z-index:2;">
                        <i class="mdi mdi-drag-vertical text-white" style="font-size:13px;"></i>
                    </span>
                </div>
                {{-- Body --}}
                <div class="card-body p-2">
                    <p class="fw-semibold mb-0 text-truncate" style="font-size:.82rem;">{{ $svc['name'] ?: '—' }}</p>
                    <p class="text-muted mb-0" style="font-size:.72rem;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                        {{ $svc['description'] ?? '' ?: '—' }}
                    </p>
                </div>
                {{-- Actions --}}
                <div class="card-footer p-1 d-flex gap-1 justify-content-end" style="background:#f8fafc;">
                    <button type="button"
                            class="btn btn-sm btn-outline-primary px-2"
                            style="font-size:.72rem;"
                            wire:click="openItemModal({{ $si }}, {{ json_encode(['name'=>'','description'=>'','image'=>'']) }})">
                        <i class="mdi mdi-pencil-outline me-1"></i>Edit
                    </button>
                    <button type="button"
                            class="btn btn-sm btn-outline-danger px-2"
                            style="font-size:.72rem;"
                            onclick="showConfirmToast('Delete this service?', function(){ window.Livewire.find('{{ $_instance->getId() }}').call('removeRowWithConfirm', {{ $si }}, '') })">
                        <i class="mdi mdi-delete me-1"></i>Delete
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@else
<div class="col-12">
    <div class="text-center py-5 rounded-3" style="border:2px dashed #cbd5e1;background:#f8fafc;">
        <i class="mdi mdi-briefcase-outline d-block" style="font-size:2.5rem;opacity:.35;"></i>
        <p class="text-muted mt-2 mb-2 small fw-semibold">No services yet.</p>
        <button type="button" class="btn btn-sm btn-primary"
                wire:click="openItemModal(null, {{ json_encode(['name'=>'','description'=>'','image'=>'']) }})">
            <i class="mdi mdi-plus me-1"></i>Add First Service
        </button>
    </div>
</div>
@endif

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- ADD / EDIT MODAL                                                       --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="ss-item-modal" tabindex="-1" aria-hidden="true" wire:ignore data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" style="max-width:500px;">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-3" style="background:linear-gradient(90deg,#eff6ff,#dbeafe);border-bottom:2px solid #bfdbfe;">
                <h5 class="modal-title fw-semibold d-flex align-items-center gap-2" style="color:#1e40af;">
                    <i class="mdi mdi-briefcase-outline"></i>
                    <span x-data x-text="$wire.editingIndex !== null ? 'Edit Service' : 'Add Service'">{{ $editingIndex !== null ? 'Edit Service' : 'Add Service' }}</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">

                    {{-- Photo --}}
                    <div class="col-12">
                        <label class="form-label small fw-semibold mb-1">Service Photo</label>
                        <div class="mb-2" x-data x-show="$wire.editingItem && $wire.editingItem.image">
                            <img x-bind:src="($wire.editingItem || {}).image || ''"
                                 alt="" class="rounded border"
                                 style="max-height:140px;max-width:100%;object-fit:cover;">
                        </div>
                        <input type="file" class="form-control form-control-sm"
                               accept="image/*" wire:model.live="uploads.itemEdit.image">
                        <div wire:loading wire:target="uploads.itemEdit.image" class="mt-1">
                            <small class="text-primary"><i class="mdi mdi-loading mdi-spin me-1"></i>Uploading…</small>
                        </div>
                        <small class="text-muted">JPG / PNG / WebP</small>
                    </div>

                    {{-- Name --}}
                    <div class="col-12">
                        <label class="form-label small fw-semibold mb-1">Service Name</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="editingItem.name"
                               placeholder="e.g. Wedding & Event Orders">
                    </div>

                    {{-- Description --}}
                    <div class="col-12">
                        <label class="form-label small fw-semibold mb-1">Description</label>
                        <textarea class="form-control form-control-sm" rows="3"
                                  wire:model="editingItem.description"
                                  placeholder="Short description of this service…"></textarea>
                    </div>

                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm px-4"
                        wire:click="saveItemModal()"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="saveItemModal">
                        <i class="mdi mdi-content-save me-1"></i>Save
                    </span>
                    <span wire:loading wire:target="saveItemModal">
                        <i class="mdi mdi-loading mdi-spin me-1"></i>Saving…
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
