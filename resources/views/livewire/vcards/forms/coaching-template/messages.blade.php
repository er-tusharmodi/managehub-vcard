{{--
 | coaching-template/messages.blade.php
 | WhatsApp / modal message templates — admin-only, hidden in client editor.
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-whatsapp me-1"></i>Message Templates <span class="badge bg-warning text-dark ms-1" style="font-size:.65rem;">Admin only</span>
    </h6>
    <small class="text-muted d-block mb-2">Pre-filled WhatsApp messages and modal copy.</small>
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="msg-wa">WA Enquiry Template</label>
    <textarea id="msg-wa" rows="2"
              class="form-control @error('form.waEnquiry') is-invalid @enderror"
              wire:model="form.waEnquiry"
              placeholder="Hi, I want to enquire about your courses..."></textarea>
    @error('form.waEnquiry') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-12 mb-3">
    <label class="form-label fw-semibold" for="msg-share">Share Text</label>
    <textarea id="msg-share" rows="2"
              class="form-control @error('form.shareText') is-invalid @enderror"
              wire:model="form.shareText"
              placeholder="Check out this coaching institute..."></textarea>
    @error('form.shareText') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="msg-dh">Demo Header</label>
    <input type="text" id="msg-dh"
           class="form-control @error('form.demoHeader') is-invalid @enderror"
           wire:model="form.demoHeader" placeholder="Free Demo Registration">
    @error('form.demoHeader') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

<div class="col-lg-6 mb-3">
    <label class="form-label fw-semibold" for="msg-dc">Demo Confirm</label>
    <input type="text" id="msg-dc"
           class="form-control @error('form.demoConfirm') is-invalid @enderror"
           wire:model="form.demoConfirm" placeholder="Confirm your demo slot">
    @error('form.demoConfirm') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
