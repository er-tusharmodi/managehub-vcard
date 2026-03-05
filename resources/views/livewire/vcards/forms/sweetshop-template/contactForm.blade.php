{{-- sweetshop-template/contactForm.blade.php — {placeholders{}, successTitle, successDescription} --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-email-edit-outline me-1"></i>Contact Form
    </h6>
</div>

<div class="col-12">
    <p class="small text-muted mb-1">Form field placeholders (hint text shown inside inputs)</p>
</div>
@foreach(['name' => 'Name Placeholder', 'mobile' => 'Mobile Placeholder', 'email' => 'Email Placeholder', 'message' => 'Message Placeholder'] as $fld => $lbl)
<div class="col-md-6">
    <label class="form-label small mb-1 fw-semibold">{{ $lbl }}</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.placeholders.{{ $fld }}" placeholder="{{ $lbl }}">
</div>
@endforeach

<div class="col-12 mt-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-1" style="font-size:.7rem;">Success Screen</h6>
</div>
<div class="col-md-6">
    <label class="form-label small mb-1 fw-semibold">Success Title</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.successTitle" placeholder="Message Sent!">
</div>
<div class="col-12">
    <label class="form-label small mb-1 fw-semibold">Success Description</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.successDescription" placeholder="We'll get back to you on WhatsApp soon.">
</div>
