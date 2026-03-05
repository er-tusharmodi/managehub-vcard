{{--
 | _shared/qr.blade.php
 | Generic QR section partial — shows note/helpText/description + downloadLabel.
 | Template-level save/download button text is shown for admin editing.
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-qrcode me-1"></i>QR Code Section
    </h6>
</div>

{{-- note / helpText / description — whichever key is present --}}
@foreach(['note', 'helpText', 'description', 'intro'] as $noteKey)
    @if(isset($form[$noteKey]) && is_string($form[$noteKey]))
    <div class="col-12 mb-3">
        <label class="form-label fw-semibold" for="qr-note">Description / Help Text</label>
        <textarea id="qr-note" rows="2"
                  class="form-control @error('form.' . $noteKey) is-invalid @enderror"
                  wire:model="form.{{ $noteKey }}"
                  placeholder="Scan to save my contact / download QR"></textarea>
        @error('form.' . $noteKey) <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    @break
    @endif
@endforeach
