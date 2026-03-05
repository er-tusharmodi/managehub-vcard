{{-- electronics-shop-template/messages.blade.php — WA/modal message templates (admin only) --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-whatsapp me-1"></i>Message Templates <span class="badge bg-warning text-dark ms-1" style="font-size:.65rem;">Admin only</span>
    </h6>
</div>
@foreach($form as $key => $value)
@if(is_string($value))
<div class="col-12 mb-3" wire:key="emsg-{{ $key }}">
    <label class="form-label fw-semibold">{{ \Illuminate\Support\Str::headline($key) }}</label>
    <textarea rows="2" class="form-control @error('form.' . $key) is-invalid @enderror"
              wire:model="form.{{ $key }}" placeholder="..."></textarea>
    @error('form.' . $key) <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
@endif
@endforeach
