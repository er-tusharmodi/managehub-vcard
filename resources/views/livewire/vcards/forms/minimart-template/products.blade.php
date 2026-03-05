{{-- minimart-template/products.blade.php — [{id, name, desc, category_key, price, oldPrice, per, tag, tagColor, product_image}] --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-package-variant me-1"></i>Products
    </h6>
</div>
@if(is_array($form))
@foreach($form as $i => $prod)
<div class="col-12 mb-2" wire:key="mmprod-{{ $i }}">
    <div class="border rounded-3 p-2 bg-light">
        <div class="row g-2">
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Name</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.name" placeholder="Amul Full Cream Milk">
            </div>
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Description</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.desc" placeholder="500ml pouch, pasteurised">
            </div>
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Category Key</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.category_key" placeholder="dairy">
            </div>
            <div class="col-sm-2">
                <label class="form-label small mb-1 fw-semibold">Price</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.price" placeholder="28">
            </div>
            <div class="col-sm-2">
                <label class="form-label small mb-1 fw-semibold">Old Price</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.oldPrice" placeholder="32">
            </div>
            <div class="col-sm-2">
                <label class="form-label small mb-1 fw-semibold">Per Unit</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.per" placeholder="500ml">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Tag</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.tag" placeholder="Fresh">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Tag Color</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.tagColor" placeholder="#27ae60">
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
