{{--
 | doctor-clinic-template/conditions.blade.php
 | Medical conditions treated: [{name, desc, query}]
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-heart-pulse me-1"></i>Conditions Treated
    </h6>
</div>

@if(is_array($form))
@foreach($form as $i => $cond)
<div class="col-12 mb-2" wire:key="cond-{{ $i }}">
    <div class="border rounded-3 p-2 bg-light">
        <div class="row g-2">
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Condition Name</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.name" placeholder="Heart Failure">
            </div>
            <div class="col-sm-5">
                <label class="form-label small mb-1 fw-semibold">Short Description</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.desc" placeholder="Advanced cardiac care">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">WA Query Pre-fill</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.query" placeholder="Heart Failure consult">
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
