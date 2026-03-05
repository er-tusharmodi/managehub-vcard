{{-- sweetshop-template/messages.blade.php — WA / toast message templates (admin only) --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-message-text-outline me-1"></i>Message Templates
        <span class="badge bg-info-subtle text-info ms-1" style="font-size:.65rem;">Admin Only</span>
    </h6>
</div>
@if(is_array($form))
@foreach($form as $msgKey => $msgVal)
@if(is_string($msgVal))
<div class="col-12 mb-2" wire:key="ssmsg-{{ $msgKey }}">
    <label class="form-label small mb-1 fw-semibold text-capitalize">
        {{ ucwords(preg_replace('/([A-Z])/', ' $1', $msgKey)) }}
    </label>
    <textarea class="form-control form-control-sm" rows="2"
              wire:model="form.{{ $msgKey }}">{{ $msgVal }}</textarea>
</div>
@endif
@endforeach
@endif
