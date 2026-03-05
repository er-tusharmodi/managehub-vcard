{{-- restaurant-cafe-template/offers.blade.php — [{icon, product_image, title, desc, tag}] --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-sale me-1"></i>Special Offers
    </h6>
</div>
@if(is_array($form))
@foreach($form as $i => $offer)
<div class="col-12 mb-2" wire:key="roffer-{{ $i }}">
    <div class="border rounded-3 p-2 bg-light">
        <div class="row g-2">
            <div class="col-sm-1">
                <label class="form-label small mb-1 fw-semibold">Icon</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.icon" placeholder="🍕">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Title</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.title" placeholder="Weekend Pizza Deal">
            </div>
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Description</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.desc" placeholder="Any 2 large pizzas for ₹599">
            </div>
            <div class="col-sm-2">
                <label class="form-label small mb-1 fw-semibold">Tag</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.tag" placeholder="Sat–Sun Only">
            </div>
            <div class="col-12">
                <label class="form-label small mb-1 fw-semibold">Offer Image URL</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.product_image" placeholder="https://…">
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
