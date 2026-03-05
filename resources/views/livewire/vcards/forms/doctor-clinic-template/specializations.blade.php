{{--
 | doctor-clinic-template/specializations.blade.php
 | List of specializations: [{name, tone, icon}]
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-stethoscope me-1"></i>Specializations
    </h6>
</div>

@if(is_array($form))
@foreach($form as $i => $spec)
<div class="col-12 mb-2" wire:key="spec-{{ $i }}">
    <div class="border rounded-3 p-2 bg-light">
        <div class="row g-2 align-items-center">
            <div class="col-sm-5">
                <label class="form-label small mb-1 fw-semibold">Specialization Name</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.name" placeholder="Cardiology">
            </div>
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Tone / Color Class</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.tone" placeholder="blue">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Icon</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.icon" placeholder="❤️">
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
