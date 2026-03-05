{{-- electronics-shop-template/brands.blade.php --}}
<div class="col-12 mb-2">
    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom mb-3">
        <span class="fw-semibold text-uppercase text-muted" style="font-size:.7rem;letter-spacing:.07em;">
            <i class="mdi mdi-store-outline me-1"></i>Brands
            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ is_array($form) ? count($form) : 0 }}</span>
        </span>
    </div>
</div>

@if(is_array($form) && !empty($form))
    @foreach($form as $bi => $brand)
    <div class="col-12 mb-1" wire:key="brand-{{ $bi }}">
        <div class="input-group input-group-sm">
            <span class="input-group-text text-muted" style="font-size:.75rem;min-width:32px;">{{ $bi + 1 }}</span>
            <input type="text" class="form-control form-control-sm"
                   wire:model="form.{{ $bi }}"
                   placeholder="Brand name">
            <button type="button" class="btn btn-sm btn-outline-danger"
                    wire:click="removeRowWithConfirm({{ $bi }}, '')"
                    wire:confirm="Remove this brand?"
                    title="Remove">
                <i class="mdi mdi-delete" style="font-size:13px;"></i>
            </button>
        </div>
    </div>
    @endforeach
@else
<div class="col-12 mb-3">
    <div class="text-center py-4 rounded-3"
         style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);border:2px dashed #cbd5e1;">
        <i class="mdi mdi-store fs-1 text-muted mb-2 d-block"></i>
        <p class="fw-semibold text-muted mb-2 small">No brands added yet</p>
    </div>
</div>
@endif

{{-- Add new brand --}}
<div class="col-12 mt-3">
    <div class="d-flex gap-2 align-items-center">
        <input type="text" class="form-control form-control-sm"
               wire:model="newItem.brand"
               wire:keydown.enter="addStringAndSave('', 'brand')"
               placeholder="New brand name (e.g. Samsung)">
        <button type="button" class="btn btn-sm btn-primary flex-shrink-0"
                wire:click="addStringAndSave('', 'brand')">
            <i class="mdi mdi-plus me-1"></i>Add
        </button>
    </div>
</div>
