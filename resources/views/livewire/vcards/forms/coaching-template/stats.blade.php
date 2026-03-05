{{--
 | coaching-template/stats.blade.php
 | Scrolling stat chips: [{id, label(hidden), suffix, static?}]
 | Only suffix is user-editable (labels are static UI text).
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-chart-line me-1"></i>Stat Chips (Suffix values)
    </h6>
    <small class="text-muted d-block mb-2">Edit the value/suffix shown on each stat chip (e.g. "1200+", "92%").</small>
</div>

@if(is_array($form))
@foreach($form as $i => $stat)
<div class="col-lg-4 mb-3" wire:key="stat-{{ $i }}">
    <label class="form-label small fw-semibold mb-1">Stat {{ $i + 1 }} Suffix</label>
    <input type="text"
           class="form-control form-control-sm @error('form.' . $i . '.suffix') is-invalid @enderror"
           wire:model="form.{{ $i }}.suffix"
           placeholder="+">
    @error('form.' . $i . '.suffix') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
@endforeach
@endif
