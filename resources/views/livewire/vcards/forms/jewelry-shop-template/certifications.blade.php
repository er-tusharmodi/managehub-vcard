{{--
 | jewelry-shop-template/certifications.blade.php
 | Certifications: [{emoji, product_image, name, sub}]
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-certificate-outline me-1"></i>Certifications
    </h6>
</div>

@if(is_array($form))
@foreach($form as $i => $cert)
<div class="col-12 mb-2" wire:key="cert-{{ $i }}">
    <div class="border rounded-3 p-2 bg-light">
        <div class="row g-2 align-items-center">
            {{-- Image --}}
            <div class="col-12 col-sm-auto">
                @php
                    $cImg = $cert['product_image'] ?? null;
                    $cSrc = $cImg ? (isset($assetBaseUrl) ? rtrim($assetBaseUrl,'/').'/'.$cImg : $cImg) : null;
                @endphp
                <div style="width:60px;">
                    @if($cSrc)
                        <img src="{{ $cSrc }}" class="rounded border mb-1" style="width:60px;height:60px;object-fit:contain;" alt="">
                    @endif
                    <input type="file" class="form-control form-control-sm p-0 border-0" style="font-size:.6rem;"
                           wire:model="form.{{ $i }}.product_image" accept="image/*">
                </div>
            </div>
            <div class="col">
                <div class="row g-2">
                    <div class="col-sm-2">
                        <label class="form-label small mb-1 fw-semibold">Emoji</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.{{ $i }}.emoji" placeholder="🛡️">
                    </div>
                    <div class="col-sm-5">
                        <label class="form-label small mb-1 fw-semibold">Name</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.{{ $i }}.name" placeholder="BIS Hallmarked">
                    </div>
                    <div class="col-sm-5">
                        <label class="form-label small mb-1 fw-semibold">Sub-text</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="form.{{ $i }}.sub" placeholder="Govt. certified purity">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
