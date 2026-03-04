{{--
 | bookshop-template/cart.blade.php
 | Shopping cart UI text labels for bookshop-template.
 | Available: $form (array)
--}}

<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-cart-outline me-1"></i>Cart UI Text
    </h6>
    <p class="text-muted mb-3" style="font-size:.82rem;">Labels displayed in the book cart panel.</p>
</div>

@foreach([
    'title'    => ['label' => 'Cart Title', 'placeholder' => 'Your Book Cart', 'col' => 'col-lg-6'],
    'empty'    => ['label' => 'Empty Cart Message', 'placeholder' => 'Your cart is empty.', 'col' => 'col-lg-6'],
    'emptySub' => ['label' => 'Empty Cart Sub-message', 'placeholder' => 'Add books to start an order!', 'col' => 'col-12'],
    'total'    => ['label' => 'Total Label', 'placeholder' => 'Total', 'col' => 'col-lg-6'],
    'order'    => ['label' => 'Order Button Text', 'placeholder' => 'Order Books via WhatsApp', 'col' => 'col-lg-6'],
    'each'     => ['label' => '"Each" Label', 'placeholder' => 'each', 'col' => 'col-lg-6'],
] as $cKey => $cCfg)
    <div class="{{ $cCfg['col'] }} mb-3">
        <label class="form-label fw-semibold" for="cart-{{ $cKey }}">{{ $cCfg['label'] }}</label>
        <input type="text"
               id="cart-{{ $cKey }}"
               class="form-control @error('form.' . $cKey) is-invalid @enderror"
               wire:model="form.{{ $cKey }}"
               placeholder="{{ $cCfg['placeholder'] }}">
        @error('form.' . $cKey) <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
@endforeach
