{{-- mens-salon-template/products.blade.php — [{name, desc, category_key, price, old, product_image, tag, tagColor}] --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-bottle-tonic me-1"></i>Grooming Products
    </h6>
</div>
@if(is_array($form))
@foreach($form as $i => $prod)
<div class="col-12 mb-2" wire:key="msprod-{{ $i }}">
    <div class="border rounded-3 p-2 bg-light">
        <div class="row g-2">
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Name</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.name" placeholder="Beard Growth Oil">
            </div>
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Description</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.desc" placeholder="Nourishes and strengthens beard">
            </div>
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Category Key</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.category_key" placeholder="beardcare">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Price</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.price" placeholder="₹249">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Old Price</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.old" placeholder="₹349">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Tag</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.tag" placeholder="Bestseller">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Tag Color</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.tagColor" placeholder="#e67e22">
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
