{{-- sweetshop-template/products.blade.php — [{id, name, desc, category_key, price, per, tag, tagColor, product_image}] --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-candy me-1"></i>Sweets / Products
    </h6>
</div>
@if(is_array($form))
@foreach($form as $i => $prod)
<div class="col-12 mb-2" wire:key="ssprod-{{ $i }}">
    <div class="border rounded-3 p-2 bg-light">
        <div class="row g-2">
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Name</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.name" placeholder="Kaju Katli">
            </div>
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Description</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.desc" placeholder="Premium cashew fudge with silver leaf">
            </div>
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Category Key</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.category_key" placeholder="milk-sweets">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Price (₹)</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.price" placeholder="800">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Per</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.per" placeholder="kg">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Tag</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.tag" placeholder="Bestseller">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Tag Color</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.tagColor" placeholder="#e74c3c">
            </div>
            <div class="col-12">
                <label class="form-label small mb-1 fw-semibold">Product Image URL</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.product_image" placeholder="https://…">
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
