{{-- minimart-template/badges.blade.php — [{icon, text}] --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-star-circle me-1"></i>Trust Badges
    </h6>
</div>
@if(is_array($form))
@foreach($form as $i => $badge)
<div class="col-12 mb-1" wire:key="mmbadge-{{ $i }}">
    <div class="input-group input-group-sm">
        <span class="input-group-text">Icon</span>
        <input type="text" class="form-control form-control-sm"
               wire:model="form.{{ $i }}.icon" placeholder="🛒" style="max-width:80px">
        <span class="input-group-text">Text</span>
        <input type="text" class="form-control form-control-sm"
               wire:model="form.{{ $i }}.text" placeholder="Fresh Every Day">
    </div>
</div>
@endforeach
@endif
