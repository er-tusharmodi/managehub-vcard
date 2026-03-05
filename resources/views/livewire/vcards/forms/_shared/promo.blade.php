{{--
 | _shared/promo.blade.php
 | Promo popup settings: enabled toggle, delay, promo body text.
 | Title and CTA button text are template-level static — not shown.
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-bullhorn-outline me-1"></i>Promo Popup
    </h6>
    <small class="text-muted d-block mb-2">Enable / disable an auto-popup promotional message.</small>
</div>

<div class="col-12 mb-3">
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" role="switch"
               id="promo-enabled" wire:model="form.enabled">
        <label class="form-check-label fw-semibold" for="promo-enabled">Enable Promo Popup</label>
    </div>
</div>

<div class="col-lg-4 mb-3">
    <label class="form-label fw-semibold" for="promo-delay">Delay (ms)</label>
    <input type="number" id="promo-delay" min="0" step="500"
           class="form-control @error('form.delayMs') is-invalid @enderror"
           wire:model="form.delayMs" placeholder="3000">
    @error('form.delayMs') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="promo-text">Promo Body Text</label>
    <textarea id="promo-text" rows="2"
              class="form-control @error('form.text') is-invalid @enderror"
              wire:model="form.text"
              placeholder="Book your appointment today and get ₹200 off!"></textarea>
    @error('form.text') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
