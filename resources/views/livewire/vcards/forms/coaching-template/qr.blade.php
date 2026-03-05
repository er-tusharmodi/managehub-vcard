{{--
 | coaching-template/qr.blade.php
 | QR section: saveText, downloadText (title/intro hidden — static)
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-qrcode me-1"></i>QR Code — Button Text
    </h6>
    <small class="text-muted d-block mb-2">Customise the action button labels on the QR section.</small>
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="qr-save">Save Contact Button</label>
    <input type="text" id="qr-save"
           class="form-control @error('form.saveText') is-invalid @enderror"
           wire:model="form.saveText" placeholder="Save My Contact">
    @error('form.saveText') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="qr-dl">Download QR Button</label>
    <input type="text" id="qr-dl"
           class="form-control @error('form.downloadText') is-invalid @enderror"
           wire:model="form.downloadText" placeholder="Download QR Code">
    @error('form.downloadText') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
