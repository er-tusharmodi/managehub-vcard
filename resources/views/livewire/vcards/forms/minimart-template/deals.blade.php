{{-- minimart-template/deals.blade.php — [{badge, name, desc, action.value}] --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-sale me-1"></i>Today's Deals
    </h6>
</div>
@if(is_array($form))
@foreach($form as $i => $deal)
<div class="col-12 mb-2" wire:key="mmdeal-{{ $i }}">
    <div class="border rounded-3 p-2 bg-white">
        <div class="row g-2 align-items-center">
            <div class="col-sm-2">
                <label class="form-label small mb-1 fw-semibold">Badge</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.badge" placeholder="20% OFF">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Name</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.name" placeholder="Weekend Combo">
            </div>
            <div class="col-sm-5">
                <label class="form-label small mb-1 fw-semibold">Description</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.desc" placeholder="Bread + Butter + Milk — ₹99">
            </div>
            <div class="col-sm-2">
                <label class="form-label small mb-1 fw-semibold">Action Value</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.action.value" placeholder="combo99">
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
