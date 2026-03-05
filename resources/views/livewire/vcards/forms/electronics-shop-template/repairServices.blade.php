{{--
 | electronics-shop-template/repairServices.blade.php
 | Repair services: [{name, sub, price, query, product_image, stroke, icon}]
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-wrench-outline me-1"></i>Repair Services
    </h6>
</div>

@if(is_array($form))
@foreach($form as $i => $svc)
<div class="col-12 mb-2" wire:key="rs-{{ $i }}">
    <div class="border rounded-3 p-2 bg-light">
        <div class="row g-2">
            {{-- Image --}}
            <div class="col-12 col-sm-auto">
                @php
                    $rsImg = $svc['product_image'] ?? null;
                    $rsSrc = $rsImg ? (isset($assetBaseUrl) ? rtrim($assetBaseUrl,'/').'/'.$rsImg : $rsImg) : null;
                @endphp
                <div style="width:60px;">
                    @if($rsSrc)
                        <img src="{{ $rsSrc }}" class="rounded border mb-1" style="width:60px;height:60px;object-fit:cover;" alt="">
                    @endif
                    <input type="file" class="form-control form-control-sm p-0 border-0" style="font-size:.6rem;"
                           wire:model="form.{{ $i }}.product_image" accept="image/*">
                </div>
            </div>
            <div class="col">
                <div class="row g-2">
                    <div class="col-sm-4">
                        <label class="form-label small mb-1 fw-semibold">Service Name</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.{{ $i }}.name" placeholder="Screen Replacement">
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label small mb-1 fw-semibold">Sub-text</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.{{ $i }}.sub" placeholder="All brands covered">
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label small mb-1 fw-semibold">Price</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.{{ $i }}.price" placeholder="From ₹499">
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label small mb-1 fw-semibold">Icon</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.{{ $i }}.icon" placeholder="📱">
                    </div>
                    <div class="col-sm-8">
                        <label class="form-label small mb-1 fw-semibold">WA Query</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.{{ $i }}.query" placeholder="Screen Replacement enquiry">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
