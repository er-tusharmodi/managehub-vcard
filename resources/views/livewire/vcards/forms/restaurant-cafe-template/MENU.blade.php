{{--
 | restaurant-cafe-template/MENU.blade.php
 | Full menu editor. MENU = dict keyed by category name → array of items.
 | Item shape: {id, name, icon, desc, price, tag, tc, product_image, veg}
--}}

<div class="col-12 mb-3">
    <h6 class="fw-semibold text-muted text-uppercase mb-1" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-silverware-fork-knife me-1"></i>Menu Categories
        <span class="badge bg-secondary-subtle text-secondary ms-1">
            {{ count(array_filter((array)$form, 'is_array')) }} categories
        </span>
    </h6>
    <small class="text-muted">Edit items within each category. Upload photo, toggle veg/non-veg, add or remove items.</small>
</div>

@foreach($form as $category => $items)
@if(!is_array($items)) @continue @endif
@php $catSlug = strtolower(preg_replace('/[^a-z0-9]/i', '-', $category)); @endphp

<div class="col-12 mb-3">
    <div class="border rounded-3 overflow-hidden shadow-sm">

        {{-- ─── Category header ──────────────────────────────────────────── --}}
        <div class="d-flex align-items-center justify-content-between px-3 py-2"
             style="background:linear-gradient(90deg,#f1f5f9,#f8fafc);">
            <div class="d-flex align-items-center gap-2"
                 style="cursor:pointer;"
                 data-bs-toggle="collapse"
                 data-bs-target="#menu-cat-{{ $catSlug }}">
                <i class="mdi mdi-food text-primary"></i>
                <span class="fw-semibold">{{ $category }}</span>
                <span class="badge bg-primary-subtle text-primary" style="font-size:.7rem;">{{ count($items) }}</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button"
                        class="btn btn-sm btn-primary px-3"
                        wire:click="addRowAndSave('{{ $category }}', ['id','name','icon','desc','price','tag','tc','product_image','veg'])">
                    <i class="mdi mdi-plus me-1"></i>Add Item
                </button>
                <i class="mdi mdi-chevron-down text-muted" style="cursor:pointer;"
                   data-bs-toggle="collapse"
                   data-bs-target="#menu-cat-{{ $catSlug }}"></i>
            </div>
        </div>

        {{-- ─── Items ────────────────────────────────────────────────────── --}}
        <div id="menu-cat-{{ $catSlug }}" class="collapse show">
            <div class="p-2">
                @forelse($items as $ii => $item)
                <div class="border rounded-2 mb-2 bg-white overflow-hidden" wire:key="menu-{{ $category }}-{{ $ii }}">

                    {{-- Item header with name preview + delete --}}
                    <div class="d-flex align-items-center justify-content-between px-3 py-1"
                         style="background:#fafafa;border-bottom:1px solid #e2e8f0;">
                        <span class="small fw-semibold text-muted">
                            #{{ $ii + 1 }}
                            @if(!empty($item['name'])) · {{ $item['name'] }} @endif
                            @if($item['veg'] ?? false)
                                <span class="badge bg-success-subtle text-success ms-1" style="font-size:.65rem;">Veg</span>
                            @endif
                        </span>
                        <button type="button"
                                class="btn btn-sm btn-outline-danger p-0 rounded-circle"
                                style="width:24px;height:24px;"
                                wire:click="removeRowWithConfirm({{ $ii }}, '{{ $category }}')"
                                wire:confirm="Remove '{{ $item['name'] ?? 'this item' }}' from {{ $category }}?">
                            <i class="mdi mdi-delete" style="font-size:11px;"></i>
                        </button>
                    </div>

                    {{-- Item fields --}}
                    <div class="p-2">
                        <div class="row g-2">

                            {{-- Product image --}}
                            <div class="col-12 col-sm-auto d-flex align-items-start">
                                @php
                                    $imgRaw = $item['product_image'] ?? null;
                                    $imgMatch = [];
                                    $imgSrc = null;
                                    if ($imgRaw && preg_match("/url\(['\"]?(.+?)['\"]?\)/i", $imgRaw, $imgMatch)) {
                                        $imgSrc = $imgMatch[1];
                                    } elseif ($imgRaw && str_starts_with($imgRaw, 'http')) {
                                        $imgSrc = $imgRaw;
                                    }
                                @endphp
                                <div style="width:72px;" class="text-center">
                                    @if($imgSrc)
                                        <img src="{{ $imgSrc }}"
                                             class="rounded border mb-1"
                                             style="width:64px;height:64px;object-fit:cover;"
                                             alt="">
                                    @else
                                        <div class="rounded border d-flex align-items-center justify-content-center mb-1"
                                             style="width:64px;height:64px;background:#f1f5f9;">
                                            <i class="mdi mdi-food text-muted fs-4"></i>
                                        </div>
                                    @endif
                                    @if(isset($uploads[$category.'.'.$ii.'.product_image']))
                                        <div class="small text-muted" style="font-size:.65rem;word-break:break-all;">
                                            {{ $uploads[$category.'.'.$ii.'.product_image']->getClientOriginalName() }}
                                        </div>
                                    @endif
                                    <input type="file" accept="image/*"
                                           class="form-control form-control-sm p-0 border-0"
                                           style="font-size:.7rem;width:64px;"
                                           wire:model="uploads.{{ $category }}.{{ $ii }}.product_image">
                                </div>
                            </div>

                            {{-- Name + Icon + Veg --}}
                            <div class="col">
                                <div class="row g-2">
                                    <div class="col-sm-5">
                                        <label class="form-label small mb-0 fw-semibold">Item Name</label>
                                        <input type="text"
                                               class="form-control form-control-sm"
                                               wire:model="form.{{ $category }}.{{ $ii }}.name"
                                               placeholder="Bruschetta Classica">
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-label small mb-0 fw-semibold">Icon / Emoji</label>
                                        <input type="text"
                                               class="form-control form-control-sm"
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
                                        <label class="form-label small mb-0 fw-semibold">Small Print (T&amp;C)</label>
                                        <input type="text"
                                               class="form-control form-control-sm"
                                               wire:model="form.{{ $category }}.{{ $ii }}.tc"
                                               placeholder="per serving / +taxes">
                                    </div>
                                </div>
                            </div>

                        </div>{{-- /row --}}
                    </div>{{-- /p-2 --}}
                </div>{{-- /item card --}}
                @empty
                <div class="text-center py-3 rounded-2" style="border:2px dashed #cbd5e1;background:#f8fafc;">
                    <p class="text-muted small mb-2">No items in <strong>{{ $category }}</strong> yet.</p>
                    <button type="button" class="btn btn-sm btn-primary"
                            wire:click="addRowAndSave('{{ $category }}', ['id','name','icon','desc','price','tag','tc','product_image','veg'])">
                        <i class="mdi mdi-plus me-1"></i>Add First Item
                    </button>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endforeach
