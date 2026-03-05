{{-- minimart-template/categories.blade.php — [{key, name, query, count}] --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-tag-multiple me-1"></i>Product Categories
    </h6>
</div>
@if(is_array($form))
@foreach($form as $i => $cat)
<div class="col-12 mb-1" wire:key="mmcat-{{ $i }}">
    <div class="border rounded-2 p-2 bg-white">
        <div class="row g-2 align-items-center">
            <div class="col-sm-2">
                <label class="form-label small mb-1 fw-semibold">Key</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.key" placeholder="dairy">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Name</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.name" placeholder="Dairy & Eggs">
            </div>
            <div class="col-sm-5">
                <label class="form-label small mb-1 fw-semibold">Query (search hint)</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.query" placeholder="milk eggs cheese">
            </div>
            <div class="col-sm-2">
                <label class="form-label small mb-1 fw-semibold">Count</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.count" placeholder="24">
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
