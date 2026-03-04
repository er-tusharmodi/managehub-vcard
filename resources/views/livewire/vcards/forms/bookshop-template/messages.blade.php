{{--
 | bookshop-template/messages.blade.php
 | WhatsApp / share message templates for bookshop-template.
 | Available: $form (array)
 | Note: {{name}} and {{website}} are template variables — they get replaced at runtime.
--}}

<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-message-text-outline me-1"></i>WhatsApp & Share Messages
    </h6>
    <p class="text-muted mb-3" style="font-size:.82rem;">
        Use <code>@{{name}}</code> and <code>@{{website}}</code> as placeholders — they will be replaced with actual values.
    </p>
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="msg-waEnquiry">WhatsApp Enquiry Opener</label>
    <input type="text"
           id="msg-waEnquiry"
           class="form-control @error('form.waEnquiry') is-invalid @enderror"
           wire:model="form.waEnquiry"
           placeholder="Hi! I have a book enquiry.">
    @error('form.waEnquiry') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <small class="text-muted">First message sent when user taps WhatsApp button</small>
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="msg-shareText">Share Text</label>
    <input type="text"
           id="msg-shareText"
           class="form-control @error('form.shareText') is-invalid @enderror"
           wire:model="form.shareText"
           placeholder="Check out &#123;&#123;name&#125;&#125;: &#123;&#123;website&#125;&#125;">
    @error('form.shareText') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <small class="text-muted">Text used when sharing the vCard link</small>
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="msg-orderHeader">Order Message Header</label>
    <textarea id="msg-orderHeader"
              class="form-control @error('form.orderHeader') is-invalid @enderror"
              wire:model="form.orderHeader"
              rows="2"
              placeholder="📚 *New Book Order – &#123;&#123;name&#125;&#125;*"></textarea>
    @error('form.orderHeader') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <small class="text-muted">Header for WhatsApp book orders. Supports *bold* markdown.</small>
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="msg-orderConfirm">Order Confirmation Line</label>
    <input type="text"
           id="msg-orderConfirm"
           class="form-control @error('form.orderConfirm') is-invalid @enderror"
           wire:model="form.orderConfirm"
           placeholder="Please confirm my order!">
    @error('form.orderConfirm') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="msg-contactHeader">Contact Enquiry Header</label>
    <input type="text"
           id="msg-contactHeader"
           class="form-control @error('form.contactHeader') is-invalid @enderror"
           wire:model="form.contactHeader"
           placeholder="📚 *Book Enquiry – &#123;&#123;name&#125;&#125;*">
    @error('form.contactHeader') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="msg-fallbackEmail">Fallback Email</label>
    <input type="text"
           id="msg-fallbackEmail"
           class="form-control @error('form.fallbackEmail') is-invalid @enderror"
           wire:model="form.fallbackEmail"
           placeholder="—">
    @error('form.fallbackEmail') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <small class="text-muted">Shown when no email is set</small>
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="msg-fallbackMessage">Fallback Message</label>
    <input type="text"
           id="msg-fallbackMessage"
           class="form-control @error('form.fallbackMessage') is-invalid @enderror"
           wire:model="form.fallbackMessage"
           placeholder="No message">
    @error('form.fallbackMessage') <div class="invalid-feedback">{{ $message }}</div> @enderror
    <small class="text-muted">Shown when no message is provided</small>
</div>
