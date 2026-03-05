{{--
 | jewelry-shop-template/collections.blade.php
 | Collections: [{name, metal, desc, price, oldPrice, product_image, tag, tagColor, category_key}]
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-diamond-stone me-1"></i>Collections
    </h6>
</div>

@if(is_array($form))
@foreach($form as $i => $item)
<div class="col-12 mb-3" wire:key="jcol-{{ $i }}">
    <div class="border rounded-3 p-2 bg-white">
        <div class="row g-2">
            {{-- Image --}}
            <div class="col-12 col-sm-auto">
                @php
                    $jImg = $item['product_image'] ?? null;
                    $jSrc = $jImg ? (isset($assetBaseUrl) ? rtrim($assetBaseUrl,'/').'/'.$jImg : $jImg) : null;
                @endphp
                <div style="width:72px;">
                    @if($jSrc)
                        <img src="{{ $jSrc }}" class="rounded border mb-1" style="width:72px;height:72px;object-fit:cover;" alt="">
                    @endif
                    <input type="file" class="form-control form-control-sm p-0 border-0" style="font-size:.65rem;"
                           wire:model="form.{{ $i }}.product_image" accept="image/*">
                </div>
            </div>
            <div class="col">
                <div class="row g-2">
                    <div class="col-sm-6">
                        <label class="form-label small mb-1 fw-semibold">Piece Name</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.{{ $i }}.name" placeholder="Bridal Necklace Set">
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small mb-1 fw-semibold">Metal</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.{{ $i }}.metal" placeholder="22K Gold">
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small mb-1 fw-semibold">Category Key</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.{{ $i }}.category_key" placeholder="gold">
                    </div>
                    <div class="col-12">
                        <label class="form-label small mb-1 fw-semibold">Description</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.{{ $i }}.desc" placeholder="Handcrafted with ethically sourced gemstones">
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small mb-1 fw-semibold">Price</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.{{ $i }}.price" placeholder="₹1,24,000">
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small mb-1 fw-semibold">Old Price</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.{{ $i }}.oldPrice" placeholder="₹1,45,000">
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small mb-1 fw-semibold">Tag</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.{{ $i }}.tag" placeholder="New">
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small mb-1 fw-semibold">Tag Color</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.{{ $i }}.tagColor" placeholder="gold">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
