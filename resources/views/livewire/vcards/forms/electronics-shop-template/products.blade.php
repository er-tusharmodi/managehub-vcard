{{--
 | electronics-shop-template/products.blade.php
 | Products list: [{name, brand, spec, category_key, price, oldPrice, product_image, tag, tagColor}]
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-devices me-1"></i>Products
    </h6>
</div>

@if(is_array($form))
@foreach($form as $i => $product)
<div class="col-12 mb-3" wire:key="prod-{{ $i }}">
    <div class="border rounded-3 p-2 bg-white">
        <div class="row g-2">
            {{-- Image --}}
            <div class="col-12 col-sm-auto">
                @php
                    $pImg = $product['product_image'] ?? null;
                    $pSrc = $pImg ? (isset($assetBaseUrl) ? rtrim($assetBaseUrl,'/').'/'.$pImg : $pImg) : null;
                @endphp
                <div style="width:72px;">
                    @if($pSrc)
                        <img src="{{ $pSrc }}" class="rounded border mb-1" style="width:72px;height:72px;object-fit:cover;" alt="">
                    @endif
                    <input type="file" class="form-control form-control-sm p-0 border-0" style="font-size:.65rem;"
                           wire:model="form.{{ $i }}.product_image" accept="image/*">
                </div>
            </div>
            <div class="col">
                <div class="row g-2">
                    <div class="col-sm-6">
                        <label class="form-label small mb-1 fw-semibold">Product Name</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.{{ $i }}.name" placeholder="Samsung Galaxy S24">
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small mb-1 fw-semibold">Brand</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.{{ $i }}.brand" placeholder="Samsung">
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small mb-1 fw-semibold">Category Key</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.{{ $i }}.category_key" placeholder="mobiles">
                    </div>
                    <div class="col-12">
                        <label class="form-label small mb-1 fw-semibold">Spec / Description</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.{{ $i }}.spec" placeholder="6.2&quot; AMOLED, 8GB RAM, 256GB">
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small mb-1 fw-semibold">Price</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.{{ $i }}.price" placeholder="₹74,999">
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small mb-1 fw-semibold">Old Price</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.{{ $i }}.oldPrice" placeholder="₹84,999">
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small mb-1 fw-semibold">Tag</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.{{ $i }}.tag" placeholder="New">
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small mb-1 fw-semibold">Tag Color</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.{{ $i }}.tagColor" placeholder="blue">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
