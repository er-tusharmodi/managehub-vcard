{{-- sweetshop-template/products.blade.php --}}
{{-- Items: [{id, name, desc, category_key, price, per, tag, tagColor, product_image}] --}}
@php
    $items  = is_array($form) ? array_values($form) : [];
    // Normalize categoryOptions — AdminSectionEditor returns [['key'=>...,'label'=>...]]
    // while TemplateVisualEditor returns ['key' => 'label'] flat map. Handle both.
    $catMap = [];
    foreach ($categoryOptions ?? [] as $ck => $cv) {
        if (is_array($cv)) {
            $catMap[$cv['key'] ?? $cv['value'] ?? $ck] = $cv['label'] ?? $cv['key'] ?? $ck;
        } else {
            $catMap[$ck] = $cv;
        }
    }
@endphp

{{-- ── Header ── --}}
<div class="col-12 mb-2">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
                <i class="mdi mdi-candy-outline me-1"></i>Products
                <span class="badge bg-primary-subtle text-primary-emphasis ms-1">{{ count($items) }}</span>
            </h6>
            <small class="text-muted">Drag <i class="mdi mdi-drag-vertical"></i> to reorder rows.</small>
        </div>
        @php $defaultProd = ['id'=>'','name'=>'','desc'=>'','category_key'=>'','price'=>'','per'=>'','tag'=>'','tagColor'=>'#e74c3c','product_image'=>'']; @endphp
        <button type="button" class="btn btn-primary btn-sm px-3"
                wire:click="openItemModal(null, {{ json_encode($defaultProd) }})">
            <i class="mdi mdi-plus me-1"></i>Add Product
        </button>
    </div>
</div>

{{-- ── Category filter tabs ── --}}
@if(count($catMap) > 0)
<div class="col-12 mb-1">
    <div class="d-flex flex-wrap gap-1" id="ss-prod-cat-tabs">
        <button type="button" class="btn btn-sm btn-primary ss-prod-tab-btn" data-cat="all">
            All
            <span class="badge bg-white text-primary ms-1" style="font-size:.65rem;">{{ count($items) }}</span>
        </button>
        @foreach($catMap as $catKey => $catLabel)
        @php $catCount = count(array_filter($items, fn($p) => ($p['category_key'] ?? '') === $catKey)); @endphp
        <button type="button" class="btn btn-sm btn-outline-primary ss-prod-tab-btn" data-cat="{{ $catKey }}">
            {{ $catLabel }}
            @if($catCount > 0)<span class="badge bg-primary-subtle text-primary ms-1" style="font-size:.65rem;">{{ $catCount }}</span>@endif
        </button>
        @endforeach
    </div>
</div>
@endif

{{-- ── Table ── --}}
<div class="col-12">
@if(count($items) > 0)
    <div class="table-responsive rounded border">
        <table class="table table-hover table-sm mb-0 align-middle" style="font-size:.8rem;">
            <thead class="table-light">
                <tr>
                    <th style="width:28px;"></th>
                    <th style="width:48px;">#</th>
                    <th style="width:52px;">Photo</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Tag</th>
                    <th style="width:80px;"></th>
                </tr>
            </thead>
            <tbody data-sort-path="">
                @foreach($items as $pi => $prod)
                <tr class="ss-prod-row" data-cat="{{ $prod['category_key'] ?? '' }}" data-row-index="{{ $pi }}">
                    <td>
                        <span class="drag-handle d-flex align-items-center justify-content-center text-muted" style="cursor:grab;width:22px;height:22px;">
                            <i class="mdi mdi-drag-vertical" style="font-size:14px;"></i>
                        </span>
                    </td>
                    <td class="text-muted">{{ $pi + 1 }}</td>
                    <td>
                        @php $pImg = $prod['product_image'] ?? ''; @endphp
                        @if($pImg)
                            <img src="{{ $pImg }}" alt="" class="rounded" style="width:40px;height:40px;object-fit:cover;">
                        @else
                            <div class="rounded bg-light d-flex align-items-center justify-content-center text-muted" style="width:40px;height:40px;">
                                <i class="mdi mdi-image-outline" style="font-size:16px;"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <span class="fw-semibold d-block">{{ $prod['name'] ?? '—' }}</span>
                        <span class="text-muted" style="font-size:.72rem;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden;">
                            {{ $prod['desc'] ?? '' }}
                        </span>
                    </td>
                    <td class="text-muted">
                        {{ $catMap[$prod['category_key'] ?? ''] ?? ($prod['category_key'] ?? '—') }}
                    </td>
                    <td>
                        @if($prod['price'] ?? '')
                            <span class="fw-semibold">₹{{ $prod['price'] }}</span>
                            @if($prod['per'] ?? '') <span class="text-muted small">/{{ $prod['per'] }}</span> @endif
                        @else —
                        @endif
                    </td>
                    <td>
                        @if($prod['tag'] ?? '')
                            <span class="badge rounded-pill"
                                  style="font-size:.65rem;background:{{ $prod['tagColor'] ?? '#6c757d' }};color:#fff;">
                                {{ $prod['tag'] }}
                            </span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <button type="button" class="btn btn-sm btn-outline-primary px-1 ss-edit-btn"
                                    title="Edit">
                                <i class="mdi mdi-pencil-outline"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger px-1 ss-delete-btn"
                                    title="Delete">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="text-center py-5 rounded-3" style="border:2px dashed #cbd5e1;background:#f8fafc;">
        <i class="mdi mdi-candy-outline d-block" style="font-size:2.5rem;opacity:.35;"></i>
        <p class="text-muted mt-2 mb-2 small fw-semibold">No products yet.</p>
        <button type="button" class="btn btn-sm btn-primary"
                wire:click="openItemModal(null, {{ json_encode($defaultProd) }})">
            <i class="mdi mdi-plus me-1"></i>Add First Product
        </button>
    </div>
@endif
</div>

<script>
// Updated on every render so the default item is always current.
window.ssProdDefault = @json($defaultProd);

(function () {
    function getCatFromUrl() {
        return new URLSearchParams(window.location.search).get('cat') || 'all';
    }

    window._ssProdActiveCat = window._ssProdActiveCat || getCatFromUrl();

    // Re-defined on every render so it is always current.
    window.ssProdFilterApply = function(cat) {
        window._ssProdActiveCat = cat;
        var url = new URL(window.location.href);
        if (cat === 'all') { url.searchParams.delete('cat'); } else { url.searchParams.set('cat', cat); }
        history.replaceState({ cat: cat }, '', url.toString());
        var tabs = document.getElementById('ss-prod-cat-tabs');
        if (tabs) {
            tabs.querySelectorAll('.ss-prod-tab-btn').forEach(function(b) {
                var isSel = b.dataset.cat === cat;
                b.classList.toggle('btn-primary', isSel);
                b.classList.toggle('btn-outline-primary', !isSel);
            });
        }
        document.querySelectorAll('tr.ss-prod-row').forEach(function(tr) {
            tr.style.display = (cat === 'all' || tr.getAttribute('data-cat') === cat) ? '' : 'none';
        });
    };

    if (!window._ssProdListeners) {
        window._ssProdListeners = true;

        // Tab click + edit/delete row delegation
        document.addEventListener('click', function(e) {
            var tabBtn = e.target.closest('.ss-prod-tab-btn');
            if (tabBtn) { window.ssProdFilterApply(tabBtn.dataset.cat || 'all'); return; }

            var editBtn   = e.target.closest('.ss-edit-btn');
            var deleteBtn = e.target.closest('.ss-delete-btn');
            if (!editBtn && !deleteBtn) return;
            var row = (editBtn || deleteBtn).closest('tr.ss-prod-row');
            if (!row) return;
            var idx = parseInt(row.dataset.rowIndex);
            var wireEl = row.closest('[wire\\:id]');
            if (!wireEl) return;
            var comp = window.Livewire.find(wireEl.getAttribute('wire:id'));
            if (!comp) return;
            var def = window.ssProdDefault || {};
            if (editBtn) {
                comp.call('openItemModal', idx, def);
            } else {
                showConfirmToast('Delete this product?', function() {
                    comp.call('removeRowWithConfirm', idx, '');
                });
            }
        });

        // Re-apply filter after every Livewire DOM update
        document.addEventListener('livewire:updated', function() {
            var cat = window._ssProdActiveCat || 'all';
            if (cat === 'all') return;
            var tabs = document.getElementById('ss-prod-cat-tabs');
            if (!tabs) return;
            var activeBtn = tabs.querySelector('[data-cat="' + cat + '"]');
            if (!activeBtn) {
                window._ssProdActiveCat = 'all';
                var url = new URL(window.location.href);
                url.searchParams.delete('cat');
                history.replaceState({ cat: 'all' }, '', url.toString());
                return;
            }
            window.ssProdFilterApply(cat);
        });

        // Apply on initial load if ?cat= is already set
        document.addEventListener('livewire:initialized', function() {
            var cat = getCatFromUrl();
            if (cat !== 'all') window.ssProdFilterApply(cat);
        });
    }
})();
</script>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- ADD / EDIT MODAL                                                       --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="ss-item-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-3" style="background:linear-gradient(90deg,#eff6ff,#dbeafe);border-bottom:2px solid #bfdbfe;">
                <h5 class="modal-title fw-semibold d-flex align-items-center gap-2" style="color:#1e40af;">
                    <i class="mdi mdi-candy-outline"></i>
                    {{ $editingIndex !== null ? 'Edit Product' : 'Add Product' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">

                    {{-- Product Photo --}}
                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-semibold mb-1">Product Photo</label>
                        @php $editPImg = $editingItem['product_image'] ?? ''; @endphp
                        @if($editPImg)
                            <div class="mb-2">
                                <img src="{{ $editPImg }}" alt="" class="rounded border"
                                     style="max-height:130px;max-width:100%;object-fit:cover;">
                            </div>
                        @endif
                        <input type="file" class="form-control form-control-sm"
                               accept="image/*" wire:model.live="uploads.itemEdit.product_image">
                        <div wire:loading wire:target="uploads.itemEdit.product_image" class="mt-1">
                            <small class="text-primary"><i class="mdi mdi-loading mdi-spin me-1"></i>Uploading…</small>
                        </div>
                        <small class="text-muted">JPG / PNG / WebP</small>
                    </div>

                    <div class="col-12 col-md-8">
                        <div class="row g-2">
                            {{-- Name --}}
                            <div class="col-12">
                                <label class="form-label small fw-semibold mb-1">Product Name</label>
                                <input type="text" class="form-control form-control-sm"
                                       wire:model="editingItem.name"
                                       placeholder="e.g. Kaju Katli">
                            </div>
                            {{-- Description --}}
                            <div class="col-12">
                                <label class="form-label small fw-semibold mb-1">Description</label>
                                <textarea class="form-control form-control-sm" rows="2"
                                          wire:model="editingItem.desc"
                                          placeholder="Premium cashew fudge with silver leaf"></textarea>
                            </div>
                            {{-- Category --}}
                            <div class="col-12">
                                <label class="form-label small fw-semibold mb-1">Category</label>
                                <select class="form-select form-select-sm" wire:model="editingItem.category_key">
                                    <option value="">— Select category —</option>
                                    @foreach($catMap as $catKey => $catLabel)
                                        <option value="{{ $catKey }}">{{ $catLabel }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Price / Per --}}
                    <div class="col-6 col-md-3">
                        <label class="form-label small fw-semibold mb-1">Price (₹)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">₹</span>
                            <input type="text" class="form-control form-control-sm"
                                   wire:model="editingItem.price" placeholder="800">
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label small fw-semibold mb-1">Per Unit</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="editingItem.per" placeholder="kg">
                    </div>

                    {{-- Tag / Tag Color --}}
                    <div class="col-6 col-md-4">
                        <label class="form-label small fw-semibold mb-1">Tag Label</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="editingItem.tag" placeholder="Bestseller">
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label small fw-semibold mb-1">Tag Color</label>
                        <input type="color" class="form-control form-control-sm form-control-color"
                               wire:model="editingItem.tagColor" value="#e74c3c">
                    </div>

                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm px-4"
                        wire:click="saveItemModal()"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="saveItemModal">
                        <i class="mdi mdi-content-save me-1"></i>Save
                    </span>
                    <span wire:loading wire:target="saveItemModal">
                        <i class="mdi mdi-loading mdi-spin me-1"></i>Saving…
                    </span>
                </button>
            </div>
        </div>
    </div>
</div>
