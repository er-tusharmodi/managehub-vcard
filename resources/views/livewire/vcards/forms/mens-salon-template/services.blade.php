{{-- mens-salon-template/services.blade.php — [{name, desc, price, dur, product_image, icon}] --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-scissors-cutting me-1"></i>Services
    </h6>
</div>
@if(is_array($form))
@foreach($form as $i => $svc)
<div class="col-12 mb-2" wire:key="msvc-{{ $i }}">
    <div class="border rounded-3 p-2 bg-light">
        <div class="row g-2">
            <div class="col-sm-1">
                <label class="form-label small mb-1 fw-semibold">Icon</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.icon" placeholder="✂️">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Name</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.name" placeholder="Haircut (Regular)">
            </div>
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Description</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.desc" placeholder="Classic scissor/clipper cut">
            </div>
            <div class="col-sm-2">
                <label class="form-label small mb-1 fw-semibold">Price</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.price" placeholder="₹149">
            </div>
            <div class="col-sm-2">
                <label class="form-label small mb-1 fw-semibold">Duration</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.dur" placeholder="20 min">
            </div>
            <div class="col-12">
                <label class="form-label small mb-1 fw-semibold">Service Image URL</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.product_image" placeholder="https://…">
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
