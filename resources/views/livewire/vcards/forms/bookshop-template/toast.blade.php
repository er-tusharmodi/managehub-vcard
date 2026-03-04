{{--
 | bookshop-template/toast.blade.php
 | Pop-up notification text for bookshop-template.
 | Available: $form (array)
--}}

<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-bell-outline me-1"></i>Toast Notification Messages
    </h6>
    <p class="text-muted mb-3" style="font-size:.82rem;">Short messages shown as pop-up notifications during user actions.</p>
</div>

@foreach([
    'contactSaved'        => ['label' => 'Contact Saved', 'placeholder' => 'Contact saved to your phone!'],
    'qrNotReady'          => ['label' => 'QR Not Ready', 'placeholder' => 'QR not ready'],
    'qrDownloaded'        => ['label' => 'QR Downloaded', 'placeholder' => 'QR Code downloaded!'],
    'linkCopied'          => ['label' => 'Link Copied', 'placeholder' => 'Link copied to clipboard!'],
    'namePhoneRequired'   => ['label' => 'Name & Phone Required', 'placeholder' => 'Name and phone are required'],
] as $tKey => $tCfg)
    <div class="col-12 mb-3">
        <label class="form-label fw-semibold" for="toast-{{ $tKey }}">{{ $tCfg['label'] }}</label>
        <input type="text"
               id="toast-{{ $tKey }}"
               class="form-control @error('form.' . $tKey) is-invalid @enderror"
               wire:model="form.{{ $tKey }}"
               placeholder="{{ $tCfg['placeholder'] }}">
        @error('form.' . $tKey) <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
@endforeach
