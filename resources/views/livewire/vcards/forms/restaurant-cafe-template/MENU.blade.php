{{--
 | restaurant-cafe-template/MENU.blade.php
 | Full menu editor for restaurant-cafe-template.
 | MENU is a dict keyed by category name, each value is a list of menu items.
 | Item shape: {id, name, icon, desc, price, tag, tc, product_image, veg}
--}}

<div class="col-12 mb-3">
    <h6 class="fw-semibold text-muted text-uppercase mb-1" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-silverware-fork-knife me-1"></i>Menu Categories
    </h6>
    <small class="text-muted">Edit items within each category. Upload a photo per item, toggle veg/non-veg.</small>
</div>

@foreach($form as $category => $items)
@if(is_array($items))
<div class="col-12 mb-3">
    {{-- Category accordion header --}}
    <div class="border rounded-3 overflow-hidden">
        <div class="d-flex align-items-center justify-content-between px-3 py-2"
             style="background:#f1f5f9;cursor:pointer;"
             data-bs-toggle="collapse"
             data-bs-target="#menu-cat-{{ strtolower(preg_replace('/[^a-z0-9]/i','-',$category)) }}"
             aria-expanded="true">
            <span class="fw-semibold">
                <i class="mdi mdi-food me-1 text-primary"></i>{{ $category }}
                <span class="badge bg-secondary ms-1" style="font-size:.7rem;">{{ count($items) }} items</span>
            </span>
            <i class="mdi mdi-chevron-down text-muted"></i>
        </div>

        <div id="menu-cat-{{ strtolower(preg_replace('/[^a-z0-9]/i','-',$category)) }}" class="collapse show">
            <div class="p-2">
                @foreach($items as $ii => $item)
                <div class="border rounded-2 p-2 mb-2 bg-white" wire:key="menu-{{ $category }}-{{ $ii }}">
                    <div class="row g-2">

                        {{-- Product image --}}
                        <div class="col-12 col-sm-auto d-flex align-items-center">
                            @php
                                $imgVal = $item['product_image'] ?? null;
                                $imgSrc = $imgVal ? (isset($assetBaseUrl) ? rtrim($assetBaseUrl,'/').'/'.$imgVal : $imgVal) : null;
                            @endphp
                            <div style="width:72px;" class="text-center">
                                @if($imgSrc)
                                    <img src="{{ $imgSrc }}"
                                         class="rounded border mb-1"
                                         style="width:64px;height:64px;object-fit:cover;"
                                         alt="Item image">
                                @else
                                    <div class="rounded border bg-light d-flex align-items-center justify-content-center mb-1"
                                         style="width:64px;height:64px;">
                                        <i class="mdi mdi-food text-muted" style="font-size:1.5rem;"></i>
                                    </div>
                                @endif
                                <input type="file"
                                       class="form-control form-control-sm p-0 border-0"
                                       style="font-size:.65rem;"
                                       wire:model="form.{{ $category }}.{{ $ii }}.product_image"
                                       accept="image/*">
                            </div>
                        </div>

                        {{-- Main fields --}}
                        <div class="col">
                            <div class="row g-2">

                                {{-- Name + Icon --}}
                                <div class="col-sm-7">
                                    <label class="form-label small mb-0 fw-semibold">Item Name</label>
                                    <input type="text"
                                           class="form-control form-control-sm"
                                           wire:model="form.{{ $category }}.{{ $ii }}.name"
                                           placeholder="e.g. Margherita Pizza">
                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label small mb-0 fw-semibold">Icon/Emoji</label>
                                    <input type="text"
                                           class="form-control form-control-sm text-center"
                                           wire:model="form.{{ $category }}.{{ $ii }}.icon"
                                           placeholder="🍕">
                                </div>
                                <div class="col-sm-3 d-flex align-items-end">
                                    <div class="form-check form-switch mb-1 ms-2">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               role="switch"
                                               id="veg-{{ $category }}-{{ $ii }}"
                                               wire:model="form.{{ $category }}.{{ $ii }}.veg">
                                        <label class="form-check-label small" for="veg-{{ $category }}-{{ $ii }}">
                                            <span class="text-success fw-semibold">Veg</span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Description --}}
                                <div class="col-12">
                                    <label class="form-label small mb-0 fw-semibold">Description</label>
                                    <textarea class="form-control form-control-sm"
                                              wire:model="form.{{ $category }}.{{ $ii }}.desc"
                                              rows="1"
                                              placeholder="Short description of the dish..."></textarea>
                                </div>

                                {{-- Price + Tag + T&C --}}
                                <div class="col-sm-3">
                                    <label class="form-label small mb-0 fw-semibold">Price</label>
                                    <input type="text"
                                           class="form-control form-control-sm"
                                           wire:model="form.{{ $category }}.{{ $ii }}.price"
                                           placeholder="₹299">
                                </div>
                                <div class="col-sm-4">
                                    <label class="form-label small mb-0 fw-semibold">Tag / Badge</label>
                                    <input type="text"
                                           class="form-control form-control-sm"
                                           wire:model="form.{{ $category }}.{{ $ii }}.tag"
                                           placeholder="Bestseller">
                                </div>
                                <div class="col-sm-5">
                                    <label class="form-label small mb-0 fw-semibold">Small Print (T&C)</label>
                                    <input type="text"
                                           class="form-control form-control-sm"
                                           wire:model="form.{{ $category }}.{{ $ii }}.tc"
                                           placeholder="per serving / +taxes">
                                </div>

                            </div>{{-- /row --}}
                        </div>{{-- /col --}}

                    </div>{{-- /row --}}
                </div>{{-- /item card --}}
                @endforeach

                @if(empty($items))
                    <p class="text-muted small text-center py-2 mb-0">No items in this category yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
@endforeach
