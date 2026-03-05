{{-- sweetshop-template/services.blade.php — [{name, description, image}] --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-room-service me-1"></i>Services
    </h6>
</div>
@if(is_array($form))
@foreach($form as $i => $svc)
<div class="col-12 mb-2" wire:key="sssvc-{{ $i }}">
    <div class="border rounded-3 p-2 bg-light">
        <div class="row g-2">
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Name</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.name" placeholder="Custom Order">
            </div>
            <div class="col-sm-8">
                <label class="form-label small mb-1 fw-semibold">Description</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.description" placeholder="Personalised sweets for events & gifts">
            </div>
            <div class="col-12">
                <label class="form-label small mb-1 fw-semibold">Image URL</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.image" placeholder="https://…">
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
