{{--
 | electronics-shop-template/featured.blade.php
 | Featured section settings: emiNote (badge is static — hidden)
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-star-outline me-1"></i>Featured Products Section
    </h6>
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="feat-emi">EMI Note</label>
    <input type="text" id="feat-emi"
           class="form-control @error('form.emiNote') is-invalid @enderror"
           wire:model="form.emiNote" placeholder="EMI available on all products above ₹5,000">
    @error('form.emiNote') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
