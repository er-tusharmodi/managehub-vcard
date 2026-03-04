{{--
 | bookshop-template/floatingBar.blade.php
 | Floating bottom bar button labels for bookshop-template.
 | Available: $form (array)
--}}

<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-dock-bottom me-1"></i>Floating Action Bar Labels
    </h6>
    <p class="text-muted mb-3" style="font-size:.82rem;">Button text shown in the sticky bar at the bottom of the vCard.</p>
</div>

@foreach([
    'call'      => ['label' => 'Call Button', 'icon' => 'mdi-phone', 'placeholder' => 'Call'],
    'whatsapp'  => ['label' => 'WhatsApp Button', 'icon' => 'mdi-whatsapp', 'placeholder' => 'WhatsApp'],
    'save'      => ['label' => 'Save Contact Button', 'icon' => 'mdi-account-plus', 'placeholder' => 'Save'],
    'cart'      => ['label' => 'Cart Button', 'icon' => 'mdi-cart-outline', 'placeholder' => 'Cart'],
] as $fKey => $fCfg)
    <div class="col-lg-6 mb-3">
        <label class="form-label fw-semibold" for="fb-{{ $fKey }}">
            <i class="mdi {{ $fCfg['icon'] }} me-1 text-muted"></i>{{ $fCfg['label'] }}
        </label>
        <input type="text"
               id="fb-{{ $fKey }}"
               class="form-control @error('form.' . $fKey) is-invalid @enderror"
               wire:model="form.{{ $fKey }}"
               placeholder="{{ $fCfg['placeholder'] }}">
        @error('form.' . $fKey) <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
@endforeach
