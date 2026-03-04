{{--
 | bookshop-template/footer.blade.php
 | Footer text for bookshop-template.
 | Available: $form (array)
--}}

<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-page-layout-footer me-1"></i>Footer Text
    </h6>
    <p class="text-muted mb-3" style="font-size:.82rem;">
        Renders as: <em>Prefix</em> · Business Name · <em>Suffix</em>
    </p>
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="footer-prefix">Prefix</label>
    <input type="text"
           id="footer-prefix"
           class="form-control @error('form.prefix') is-invalid @enderror"
           wire:model="form.prefix"
           placeholder="© 2026">
    @error('form.prefix') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <small class="text-muted">e.g. © 2026</small>
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="footer-suffix">Suffix</label>
    <input type="text"
           id="footer-suffix"
           class="form-control @error('form.suffix') is-invalid @enderror"
           wire:model="form.suffix"
           placeholder="· Handcrafted with ❤️ for Book Lovers">
    @error('form.suffix') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <small class="text-muted">e.g. · All rights reserved</small>
</div>
