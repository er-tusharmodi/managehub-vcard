{{-- mens-salon-template/barbers.blade.php — [{avatar, gradient, name, role, exp, skills[]}] --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-account-group me-1"></i>Barbers / Team
    </h6>
</div>
@if(is_array($form))
@foreach($form as $i => $barber)
<div class="col-12 mb-2" wire:key="mbar-{{ $i }}">
    <div class="border rounded-3 p-2 bg-light">
        <div class="row g-2">
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Name</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.name" placeholder="Rajesh Kumar">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Role</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.role" placeholder="Senior Barber">
            </div>
            <div class="col-sm-2">
                <label class="form-label small mb-1 fw-semibold">Experience</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.exp" placeholder="10 yrs">
            </div>
            <div class="col-sm-2">
                <label class="form-label small mb-1 fw-semibold">Avatar (initials)</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.avatar" placeholder="RK">
            </div>
            <div class="col-sm-2">
                <label class="form-label small mb-1 fw-semibold">Gradient CSS</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.gradient" placeholder="linear-gradient(…)">
            </div>
            @if(is_array($form[$i]['skills'] ?? null))
            <div class="col-12">
                <label class="form-label small mb-1 fw-semibold">Skills (one per entry)</label>
                @foreach(($form[$i]['skills'] ?? []) as $si => $skill)
                <input type="text" class="form-control form-control-sm mb-1"
                       wire:model="form.{{ $i }}.skills.{{ $si }}" wire:key="mskill-{{ $i }}-{{ $si }}"
                       placeholder="Fade Expert">
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endforeach
@endif
