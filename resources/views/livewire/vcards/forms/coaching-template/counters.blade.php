{{--
 | coaching-template/counters.blade.php
 | Three animated stat counters: years, selections, successRate
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-counter me-1"></i>Achievement Counters
    </h6>
</div>

<div class="col-lg-4 mb-3">
    <label class="form-label fw-semibold" for="cnt-years">Years of Experience</label>
    <input type="number" id="cnt-years" min="0"
           class="form-control @error('form.years') is-invalid @enderror"
           wire:model="form.years" placeholder="15">
    @error('form.years') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-4 mb-3">
    <label class="form-label fw-semibold" for="cnt-sel">Selections</label>
    <input type="number" id="cnt-sel" min="0"
           class="form-control @error('form.selections') is-invalid @enderror"
           wire:model="form.selections" placeholder="1200">
    @error('form.selections') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-4 mb-3">
    <label class="form-label fw-semibold" for="cnt-rate">Success Rate (%)</label>
    <input type="number" id="cnt-rate" min="0" max="100"
           class="form-control @error('form.successRate') is-invalid @enderror"
           wire:model="form.successRate" placeholder="92">
    @error('form.successRate') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
