{{-- restaurant-cafe-template/MENU.blade.php --}}
{{-- Items: [{id, name, icon, desc, category_key, price, op, tag, tc, product_image, veg}] --}}
@php
    $items  = is_array($form) ? array_values($form) : [];
    // Normalize categoryOptions — AdminSectionEditor returns [['key'=>...,'label'=>...]]
    // TemplateVisualEditor returns ['key' => 'label'] flat map. Handle both.
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
                <i class="mdi mdi-silverware-fork-knife me-1"></i>Menu
                <span class="badge bg-success-subtle text-success-emphasis ms-1">{{ count($items) }}</span>
            </h6>
            <small class="text-muted">Drag <i class="mdi mdi-drag-vertical"></i> to reorder rows.</small>
        </div>
        @php $rcMenuDefault = ['id'=>'','name'=>'','icon'=>'','desc'=>'','category_key'=>'','price'=>'','op'=>'','tag'=>'','tc'=>'','product_image'=>'','veg'=>false]; @endphp
        <button type="button" class="btn btn-success btn-sm px-3"
                wire:click="openItemModal(null, {{ json_encode($rcMenuDefault) }})">
            <i class="mdi mdi-plus me-1"></i>Add Item
        </button>
    </div>
</div>

{{-- ── Category filter tabs ── --}}
@if(count($catMap) > 0)
<div class="col-12 mb-1">
    <div class="d-flex flex-wrap gap-1" id="rc-menu-cat-tabs">
        <button type="button" class="btn btn-sm btn-success rc-menu-tab-btn" data-cat="all">
            All
            <span class="badge bg-white text-success ms-1" style="font-size:.65rem;">{{ count($items) }}</span>
        </button>
        @foreach($catMap as $catKey => $catLabel)
        @php $catCount = count(array_filter($items, fn($p) => ($p['category_key'] ?? '') === $catKey)); @endphp
        <button type="button" class="btn btn-sm btn-outline-success rc-menu-tab-btn" data-cat="{{ $catKey }}">
            {{ $catLabel }}
            @if($catCount > 0)<span class="badge bg-success-subtle text-success ms-1" style="font-size:.65rem;">{{ $catCount }}</span>@endif
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
                    <th style="width:40px;">#</th>
                    <th style="width:52px;">Photo</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th style="width:70px;">Veg</th>
                    <th>Tag</th>
                    <th style="width:80px;"></th>
                </tr>
            </thead>
            <tbody data-sort-path="">
                @foreach($items as $pi => $item)
                <tr class="rc-menu-row" data-cat="{{ $item['category_key'] ?? '' }}" data-row-index="{{ $pi }}">
                    <td>
                        <span class="drag-handle d-flex align-items-center justify-content-center text-muted" style="cursor:grab;width:22px;height:22px;">
                            <i class="mdi mdi-drag-vertical" style="font-size:14px;"></i>
                        </span>
                    </td>
                    <td class="text-muted">{{ $pi + 1 }}</td>
                    <td>
                        @php $pImg = $item['product_image'] ?? ''; @endphp
                        @if($pImg)
                            <img src="{{ $pImg }}" alt="" class="rounded" style="width:40px;height:40px;object-fit:cover;">
                        @else
                            <div class="rounded bg-light d-flex align-items-center justify-content-center text-muted" style="width:40px;height:40px;">
                                <i class="mdi mdi-food text-muted" style="font-size:16px;"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <span class="fw-semibold d-block">
                            @if($item['icon'] ?? '') {{ $item['icon'] }} @endif
                            {{ $item['name'] ?? '—' }}
                        </span>
                        <span class="text-muted" style="font-size:.72rem;display:-webkit-box;-webkit-line-clamp:1;-webkit-box-orient:vertical;overflow:hidden;">
                            {{ $item['desc'] ?? '' }}
                        </span>
                    </td>
                    <td class="text-muted">
                        {{ $catMap[$item['category_key'] ?? ''] ?? ($item['category_key'] ?? '—') }}
                    </td>
                    <td>
                        @if($item['price'] ?? '')
                            <span class="fw-semibold text-success-emphasis">₹{{ $item['price'] }}</span>
                            @if($item['op'] ?? '') <span class="text-muted d-block" style="font-size:.7rem;text-decoration:line-through;">₹{{ $item['op'] }}</span> @endif
                            @if($item['tc'] ?? '') <span class="text-muted" style="font-size:.7rem;">{{ $item['tc'] }}</span> @endif
                        @else —
                        @endif
                    </td>
                    <td class="text-center">
                        @if($item['veg'] ?? false)
                            <span class="badge bg-success-subtle text-success-emphasis" style="font-size:.65rem;"><i class="mdi mdi-leaf"></i> Veg</span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($item['tag'] ?? '')
                            <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill" style="font-size:.65rem;">{{ $item['tag'] }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <button type="button" class="btn btn-sm btn-outline-success px-1 rc-edit-btn" title="Edit">
                                <i class="mdi mdi-pencil-outline"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger px-1 rc-delete-btn" title="Delete">
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
    <div class="text-center py-5 rounded-3" style="border:2px dashed #bbf7d0;background:#f0fdf4;">
        <i class="mdi mdi-silverware-fork-knife d-block" style="font-size:2.5rem;opacity:.35;color:#16a34a;"></i>
        <p class="text-muted mt-2 mb-2 small fw-semibold">No menu items yet.</p>
        <button type="button" class="btn btn-sm btn-success"
                wire:click="openItemModal(null, {{ json_encode($rcMenuDefault) }})">
            <i class="mdi mdi-plus me-1"></i>Add First Item
        </button>
    </div>
@endif
</div>

<script>
// Updated on every render so the default item is always current.
window.rcMenuDefault = @json($rcMenuDefault);

(function () {
    function getCatFromUrl() {
        return new URLSearchParams(window.location.search).get('cat') || 'all';
    }

    // Persist active cat across re-renders; on first load read from URL.
    window._rcMenuActiveCat = window._rcMenuActiveCat || getCatFromUrl();

    // Re-defined on every render so it is always current.
    window.rcMenuFilterApply = function(cat) {
        window._rcMenuActiveCat = cat;

        // Sync URL query param without page reload
        var url = new URL(window.location.href);
        if (cat === 'all') { url.searchParams.delete('cat'); } else { url.searchParams.set('cat', cat); }
        history.replaceState({ cat: cat }, '', url.toString());

        var tabs = document.getElementById('rc-menu-cat-tabs');
        if (tabs) {
            tabs.querySelectorAll('.rc-menu-tab-btn').forEach(function(b) {
                var isSel = b.dataset.cat === cat;
                b.classList.toggle('btn-success', isSel);
                b.classList.toggle('btn-outline-success', !isSel);
            });
        }
        document.querySelectorAll('tr.rc-menu-row').forEach(function(tr) {
            tr.style.display = (cat === 'all' || tr.getAttribute('data-cat') === cat) ? '' : 'none';
        });
    };

    if (!window._rcMenuListeners) {
        window._rcMenuListeners = true;

        // Tab click + edit/delete row delegation
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.rc-menu-tab-btn');
            if (btn) { window.rcMenuFilterApply(btn.dataset.cat || 'all'); return; }

            var editBtn   = e.target.closest('.rc-edit-btn');
            var deleteBtn = e.target.closest('.rc-delete-btn');
            if (!editBtn && !deleteBtn) return;
            var row = (editBtn || deleteBtn).closest('tr.rc-menu-row');
            if (!row) return;
            var idx = parseInt(row.dataset.rowIndex);
            var wireEl = row.closest('[wire\\:id]');
            if (!wireEl) return;
            var comp = window.Livewire.find(wireEl.getAttribute('wire:id'));
            if (!comp) return;
            var def = window.rcMenuDefault || {};
            if (editBtn) {
                comp.call('openItemModal', idx, def);
            } else {
                showConfirmToast('Delete this menu item?', function() {
                    comp.call('removeRowWithConfirm', idx, '');
                });
            }
        });

        // Re-apply filter after every Livewire re-render
        document.addEventListener('livewire:updated', function() {
            var cat = window._rcMenuActiveCat || 'all';
            if (cat === 'all') return;
            var tabs = document.getElementById('rc-menu-cat-tabs');
            if (!tabs) return;
            var activeBtn = tabs.querySelector('[data-cat="' + cat + '"]');
            if (!activeBtn) {
                // Category was deleted — reset
                window._rcMenuActiveCat = 'all';
                var url = new URL(window.location.href);
                url.searchParams.delete('cat');
                history.replaceState({ cat: 'all' }, '', url.toString());
                return;
            }
            window.rcMenuFilterApply(cat);
        });

        // Apply on initial/refresh load if ?cat= is already set
        document.addEventListener('livewire:initialized', function() {
            var cat = getCatFromUrl();
            if (cat !== 'all') window.rcMenuFilterApply(cat);
        });
    }
})();
</script>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- ADD / EDIT ITEM MODAL                                                  --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="ss-item-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-3" style="background:linear-gradient(90deg,#f0fdf4,#dcfce7);border-bottom:2px solid #bbf7d0;">
                <h5 class="modal-title fw-semibold d-flex align-items-center gap-2" style="color:#15803d;">
                    <i class="mdi mdi-silverware-fork-knife"></i>
                    {{ $editingIndex !== null ? 'Edit Item' : 'Add Item' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">

                    {{-- Photo --}}
                    <div class="col-12 col-md-4">
                        <label class="form-label small fw-semibold mb-1">Item Photo</label>
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
                            <small class="text-success"><i class="mdi mdi-loading mdi-spin me-1"></i>Uploading…</small>
                        </div>
                        <small class="text-muted">JPG / PNG / WebP</small>
                    </div>

                    <div class="col-12 col-md-8">
                        <div class="row g-2">
                            {{-- Name + Icon --}}
                            <div class="col-8">
                                <label class="form-label small fw-semibold mb-1">Item Name</label>
                                <input type="text" class="form-control form-control-sm"
                                       wire:model="editingItem.name"
                                       placeholder="e.g. Paneer Tikka">
                            </div>
                            <div class="col-4">
                                <label class="form-label small fw-semibold mb-1">Emoji / Icon</label>
                                <input type="text" class="form-control form-control-sm"
                                       wire:model="editingItem.icon"
                                       list="rc-icon-datalist"
                                       placeholder="🍕">
                                <datalist id="rc-icon-datalist">
                                    @foreach(['🍕','🍔','🌮','🌯','🍜','🍣','🍱','🥗','🍛','🥘','🍲','🥩','🍗','🥓','🍤','🥪','🥙','🧆','🧇','🥞','🍳','🥚','🍰','🎂','🧁','🍩','🍪','🍫','☕','🍵','🧃','🥤','🍺','🍷','🍸','🍹','🥂','🧋','🌽','🥦','🥕','🧅','🧄','🫕'] as $__em)
                                    <option value="{{ $__em }}">{{ $__em }}</option>
                                    @endforeach
                                </datalist>
                            </div>
                            {{-- Description --}}
                            <div class="col-12">
                                <label class="form-label small fw-semibold mb-1">Description</label>
                                <textarea class="form-control form-control-sm" rows="2"
                                          wire:model="editingItem.desc"
                                          placeholder="Short description of the dish…"></textarea>
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

                    {{-- Price / MRP --}}
                    <div class="col-6 col-md-3">
                        <label class="form-label small fw-semibold mb-1">Price (₹)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-success-subtle border-success-subtle text-success-emphasis">₹</span>
                            <input type="text" class="form-control form-control-sm border-success-subtle"
                                   wire:model="editingItem.price" placeholder="299">
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label small fw-semibold mb-1">Original / MRP (₹)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">₹</span>
                            <input type="text" class="form-control form-control-sm"
                                   wire:model="editingItem.op" placeholder="399">
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label small fw-semibold mb-1">Small Print</label>
                        <select class="form-select form-select-sm" wire:model="editingItem.tc">
                            <option value="">— none —</option>
                            <option>per serving</option>
                            <option>per piece</option>
                            <option>per plate</option>
                            <option>per bowl</option>
                            <option>per glass</option>
                            <option>per portion</option>
                            <option>per kg</option>
                            <option>onwards</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-3 d-flex align-items-end pb-1">
                        <div class="form-check form-switch ms-1">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   id="rcVegToggle" wire:model="editingItem.veg">
                            <label class="form-check-label fw-semibold text-success small"
                                   for="rcVegToggle">Veg</label>
                        </div>
                    </div>

                    {{-- Tag --}}
                    <div class="col-12 col-md-6">
                        <label class="form-label small fw-semibold mb-1">Badge / Tag</label>
                        <input type="text" class="form-control form-control-sm"
                               wire:model="editingItem.tag" placeholder="Bestseller, Chef's Special…">
                    </div>

                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success btn-sm px-4"
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
