{{-- mens-salon-template/categories.blade.php — [{key, label}] --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-tag me-1"></i>Product Categories
    </h6>
</div>
@if(is_array($form))
@foreach($form as $i => $cat)
<div class="col-12 mb-1" wire:key="mscat-{{ $i }}">
    <div class="input-group input-group-sm">
        <span class="input-group-text small">Key</span>
        <input type="text" class="form-control form-control-sm"
               wire:model="form.{{ $i }}.key" placeholder="haircare">
        <span class="input-group-text small">Label</span>
        <input type="text" class="form-control form-control-sm"
               wire:model="form.{{ $i }}.label" placeholder="Hair Care">
    </div>
</div>
@endforeach
@endif
