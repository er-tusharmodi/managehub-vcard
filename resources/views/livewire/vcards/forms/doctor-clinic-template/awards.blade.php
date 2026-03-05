{{--
 | doctor-clinic-template/awards.blade.php
 | Awards/achievements: [{icon, name, desc}]
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-trophy-outline me-1"></i>Awards &amp; Recognitions
    </h6>
</div>

@if(is_array($form))
@foreach($form as $i => $award)
<div class="col-12 mb-2" wire:key="award-{{ $i }}">
    <div class="border rounded-3 p-2 bg-light">
        <div class="row g-2 align-items-center">
            <div class="col-sm-2">
                <label class="form-label small mb-1 fw-semibold">Icon</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.icon" placeholder="🏆">
            </div>
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Award Name</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.name" placeholder="Best Cardiologist 2023">
            </div>
            <div class="col-sm-6">
                <label class="form-label small mb-1 fw-semibold">Description</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.desc" placeholder="Awarded by Indian Heart Association">
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
