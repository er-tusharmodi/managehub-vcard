{{--
 | restaurant-cafe-template/MENU.blade.php
 | Professional menu editor: accordion per category, Quick Add with category selector.
 | MENU shape: { "Starters": [{id,name,icon,desc,price,op,tag,tc,product_image,veg}], ... }
 | Categories are driven by profile.cuisineTags (reflected as MENU keys).
--}}
@php
    $menuCategories = array_filter((array)$form, 'is_array');
    $totalItems     = array_sum(array_map('count', $menuCategories));
    $catKeys        = array_keys($menuCategories);
@endphp

<div class="col-12 mb-3">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h6 class="fw-semibold text-muted text-uppercase mb-1" style="font-size:.72rem;letter-spacing:.07em;">
                <i class="mdi mdi-silverware-fork-knife me-1"></i>Menu
            </h6>
            <small class="text-muted">
                <span class="badge bg-secondary-subtle text-secondary me-1">{{ count($menuCategories) }}</span> categories &middot;
                <span class="badge bg-secondary-subtle text-secondary me-1">{{ $totalItems }}</span> items total.
            </small>
        </div>
        <button type="button" class="btn btn-outline-success btn-sm px-3"
                data-bs-toggle="modal" data-bs-target="#addMenuCategoryModal">
            <i class="mdi mdi-shape-square-plus me-1"></i>Add Category
        </button>
    </div>
</div>

@if(empty($menuCategories))
<div class="col-12">
    <div class="rounded-3 p-4 text-center" style="border:2px dashed #cbd5e1;background:#f8fafc;">
        <i class="mdi mdi-food-off text-muted" style="font-size:2.5rem;"></i>
        <p class="text-muted mt-2 mb-1 fw-semibold">No menu categories yet.</p>
        <small class="text-muted">Go to the <strong>Profile</strong> section and add category tags first, then come back here to add items.</small>
    </div>
</div>
@else

{{-- ── Category accordions ─────────────────────────────────────────────── --}}
@foreach($menuCategories as $category => $items)
@php $catSlug = 'mc-' . preg_replace('/[^a-z0-9]/i', '-', strtolower($category)); @endphp

<div class="col-12 mb-3">
<div class="card border shadow-sm overflow-hidden" style="border-radius:.75rem;">

    {{-- Category header --}}
    <div class="card-header d-flex align-items-center justify-content-between py-2 px-3"
         style="background:linear-gradient(90deg,#f0fdf4,#dcfce7);border-bottom:1px solid #bbf7d0;cursor:pointer;"
         data-bs-toggle="collapse" data-bs-target="#{{ $catSlug }}">
        <div class="d-flex align-items-center gap-2">
            <i class="mdi mdi-food text-success"></i>
            <span class="fw-semibold text-success-emphasis">{{ $category }}</span>
            <span class="badge bg-success-subtle text-success-emphasis" style="font-size:.7rem;">
                {{ count($items) }} item{{ count($items) === 1 ? '' : 's' }}
            </span>
        </div>
        <div class="d-flex align-items-center gap-2"
             x-data="{ cat: @js($category), catLabel: @js($category . ' (' . count($items) . ' item(s))') }"
             onclick="event.stopPropagation();">
            <button type="button"
                    class="btn btn-success btn-sm px-3"
                    wire:click.stop="openMenuItemModal('{{ $category }}')">
                <i class="mdi mdi-plus me-1"></i>Add Item
            </button>
            <button type="button"
                    class="btn btn-sm btn-outline-danger p-0 rounded-circle"
                    style="width:26px;height:26px;line-height:1;"
                    x-on:click.stop="showConfirmToast('Delete this category?', () => $wire.deleteMenuCategory(cat), catLabel)">
                <i class="mdi mdi-delete-outline" style="font-size:12px;"></i>
            </button>
            <i class="mdi mdi-chevron-down text-muted ms-1"></i>
        </div>
    </div>

    {{-- Items collapsible --}}
    <div id="{{ $catSlug }}" class="collapse show">
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0" style="font-size:.82rem;">
                <thead>
                    <tr style="background:linear-gradient(90deg,#f0fdf4,#dcfce7);border-bottom:2px solid #bbf7d0;">
                        <th class="px-2 py-2 text-muted fw-semibold" style="width:28px;font-size:.68rem;">#</th>
                        <th class="py-2 text-muted fw-semibold" style="width:56px;font-size:.68rem;">Photo</th>
                        <th class="py-2 text-muted fw-semibold" style="min-width:140px;font-size:.68rem;">Name</th>
                        <th class="py-2 text-muted fw-semibold" style="font-size:.68rem;">Description</th>
                        <th class="py-2 text-muted fw-semibold" style="width:110px;font-size:.68rem;">Price</th>
                        <th class="py-2 text-muted fw-semibold" style="width:90px;font-size:.68rem;">Tag</th>
                        <th class="py-2 text-muted fw-semibold text-center" style="width:40px;font-size:.68rem;">Veg</th>
                        <th style="width:68px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $ii => $item)
                    <tr wire:key="mi-{{ $category }}-{{ $ii }}" class="border-bottom">

                        {{-- # --}}
                        <td class="px-2 text-muted fw-semibold" style="font-size:.7rem;">{{ $ii + 1 }}</td>

                        {{-- Photo --}}
                        <td class="py-1">
                            @php
                                $ir = $item['product_image'] ?? null;
                                $im = []; $is = null;
                                if ($ir && preg_match("/url\(['\"]?(.+?)['\"]?\)/i", $ir, $im)) { $is = $im[1]; }
                                elseif ($ir && (str_starts_with($ir,'http') || str_starts_with($ir,'/'))) { $is = $ir; }
                            @endphp
                            @if($is)
                                <img src="{{ $is }}" class="rounded-2 border"
                                     style="width:44px;height:44px;object-fit:cover;" alt="">
                            @else
                                <div class="rounded-2 border d-flex align-items-center justify-content-center"
                                     style="width:44px;height:44px;background:#f1f5f9;">
                                    <i class="mdi mdi-food text-muted" style="font-size:.9rem;"></i>
                                </div>
                            @endif
                        </td>

                        {{-- Name / Icon --}}
                        <td class="py-1 fw-semibold" style="font-size:.83rem;">
                            {{ $item['icon'] ?? '' }} {{ $item['name'] ?: '—' }}
                        </td>

                        {{-- Description --}}
                        <td class="py-1 text-muted" style="font-size:.78rem;">
                            {{ \Str::limit($item['desc'] ?? '', 60) ?: '—' }}
                        </td>

                        {{-- Price --}}
                        <td class="py-1">
                            @if(!empty($item['price']))
                                <span class="fw-semibold text-success-emphasis">₹{{ $item['price'] }}</span>
                            @endif
                            @if(!empty($item['op']))
                                <span class="text-muted d-block" style="font-size:.72rem;text-decoration:line-through;">₹{{ $item['op'] }}</span>
                            @endif
                            @if(empty($item['price']) && empty($item['op']))<span class="text-muted">—</span>@endif
                        </td>

                        {{-- Tag --}}
                        <td class="py-1">
                            @if(!empty($item['tag']))
                                <span class="badge bg-warning-subtle text-warning-emphasis" style="font-size:.65rem;">{{ $item['tag'] }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        {{-- Veg --}}
                        <td class="py-1 text-center">
                            @if($item['veg'] ?? false)
                                <span class="badge bg-success-subtle text-success-emphasis" style="font-size:.65rem;">
                                    <i class="mdi mdi-leaf"></i>
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="py-1 text-center" style="white-space:nowrap;">
                            <button type="button"
                                    class="btn btn-sm btn-outline-primary p-0 rounded-circle me-1"
                                    style="width:26px;height:26px;line-height:1;"
                                    wire:click="openMenuItemModal('{{ $category }}', {{ $ii }})">
                                <i class="mdi mdi-pencil-outline" style="font-size:12px;"></i>
                            </button>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger p-0 rounded-circle"
                                    style="width:26px;height:26px;line-height:1;"
                                    x-on:click="showConfirmToast('Remove this menu item?', () => $wire.removeRowWithConfirm({{ $ii }}, '{{ $category }}'), '{{ $item['name'] ?? 'this item' }}')">
                                <i class="mdi mdi-delete" style="font-size:12px;"></i>
                            </button>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-3 text-center" style="border:none;">
                            <div class="rounded-3 p-3 d-inline-block" style="border:2px dashed #bbf7d0;background:#f0fdf4;">
                                <p class="text-muted small mb-2">No items in <strong>{{ $category }}</strong> yet.</p>
                                <button type="button" class="btn btn-sm btn-success"
                                        wire:click="openMenuItemModal('{{ $category }}')">
                                    <i class="mdi mdi-plus me-1"></i>Add First Item
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>{{-- /collapse --}}
</div>{{-- /card --}}
</div>{{-- /col-12 --}}
@endforeach

@endif

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- ADD CATEGORY MODAL                                                      --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="addMenuCategoryModal" tabindex="-1" aria-labelledby="addMenuCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-3"
                 style="background:linear-gradient(90deg,#f0fdf4,#dcfce7);border-bottom:2px solid #bbf7d0;">
                <h5 class="modal-title fw-semibold d-flex align-items-center gap-2" id="addMenuCategoryModalLabel" style="font-size:.95rem;">
                    <i class="mdi mdi-shape-square-plus text-success"></i>New Category
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <label class="form-label small fw-semibold mb-1">Category Name</label>
                <input type="text" class="form-control"
                       wire:model="newItem.newCategoryName"
                       placeholder="e.g. Starters, Desserts, Drinks…"
                       wire:keydown.enter.prevent="addMenuCategory">
                <small class="text-muted mt-1 d-block">Creates a new empty category tab in the menu.</small>
            </div>
            <div class="modal-footer border-top py-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-success btn-sm px-4"
                        wire:click="addMenuCategory"
                        wire:loading.attr="disabled">
                    <span wire:loading wire:target="addMenuCategory">
                        <span class="spinner-border spinner-border-sm me-1"></span>
                    </span>
                    <i class="mdi mdi-check me-1" wire:loading.remove wire:target="addMenuCategory"></i>
                    Create
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- MENU ITEM MODAL (Add / Edit)                                            --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="menuItemModal" tabindex="-1" aria-labelledby="menuItemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-3"
                 style="background:linear-gradient(90deg,#f0fdf4,#dcfce7);border-bottom:2px solid #bbf7d0;">
                <h5 class="modal-title fw-semibold d-flex align-items-center gap-2" id="menuItemModalLabel">
                    <i class="mdi mdi-silverware-fork-knife text-success"></i>
                    @if($editingIndex !== null)
                        Edit Item @if($editingCategory) &mdash; <span class="text-success-emphasis">{{ $editingCategory }}</span>@endif
                    @else
                        Add Item @if($editingCategory) to <span class="text-success-emphasis">{{ $editingCategory }}</span>@endif
                    @endif
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">

                    {{-- Photo --}}
                    <div class="col-12">
                        <label class="form-label small fw-semibold mb-1">Photo</label>
                        <div class="d-flex align-items-center gap-3">
                            @php
                                $mIr = $editingItem['product_image'] ?? null;
                                $mIm = []; $mIs = null;
                                if ($mIr && preg_match("/url\(['\"]?(.+?)['\"]?\)/i", $mIr, $mIm)) { $mIs = $mIm[1]; }
                                elseif ($mIr && (str_starts_with($mIr,'http') || str_starts_with($mIr,'/'))) { $mIs = $mIr; }
                            @endphp
                            @if($mIs)
                                <img src="{{ $mIs }}" id="menuItemPhotoPreview" class="rounded-3 border"
                                     style="width:72px;height:72px;object-fit:cover;" alt="">
                            @else
                                <div id="menuItemPhotoPreview" class="rounded-3 border d-flex align-items-center justify-content-center"
                                     style="width:72px;height:72px;background:#f1f5f9;">
                                    <i class="mdi mdi-food text-muted fs-3"></i>
                                </div>
                            @endif
                            {{-- No wire:model — upload is handled manually on Save to prevent re-render inside modal --}}
                            <input type="file" accept="image/*" class="form-control form-control-sm"
                                   id="menuItemFileInput"
                                   onchange="var f=this.files[0];if(!f)return;window.__menuItemFile=f;var url=URL.createObjectURL(f);var p=document.getElementById('menuItemPhotoPreview');var img=document.createElement('img');img.src=url;img.id='menuItemPhotoPreview';img.className='rounded-3 border';img.style.cssText='width:72px;height:72px;object-fit:cover;';p.replaceWith(img);">
                        </div>
                    </div>

                    {{-- Name + Icon + Veg --}}
                    <div class="col-sm-6">
                        <label class="form-label small fw-semibold mb-1">Item Name</label>
                        <input type="text" class="form-control"
                               wire:model="editingItem.name"
                               placeholder="e.g. Margherita Pizza">
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label small fw-semibold mb-1">Emoji / Icon</label>
                        <input type="text" class="form-control"
                               wire:model="editingItem.icon"
                               list="menu-icon-datalist"
                               placeholder="🍕">
                        <datalist id="menu-icon-datalist">
                            @foreach(['🍕','🍔','🌮','🌯','🍜','🍣','🍱','🥗','🍛','🥘','🍲','🥩','🍗','🥓','🍤','🥪','🥙','🧆','🧇','🥞','🍳','🥚','🍰','🎂','🧁','🍩','🍪','🍫','🍭','🍮','☕','🍵','🧃','🥤','🍺','🍷','🍸','🍹','🥂','🧋','🎉','🎁','🏷️','⭐','🔥','💫','🌟','✨','🍽️','🥦','🥕','🧅','🧄','🫕','🫙'] as $__em)
                            <option value="{{ $__em }}">{{ $__em }}</option>
                            @endforeach
                        </datalist>
                    </div>
                    <div class="col-sm-2 d-flex align-items-end pb-1">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   id="modalVegToggle" wire:model="editingItem.veg">
                            <label class="form-check-label fw-semibold text-success small"
                                   for="modalVegToggle">Veg</label>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="col-12">
                        <label class="form-label small fw-semibold mb-1">Description</label>
                        <textarea class="form-control" rows="2"
                                  wire:model="editingItem.desc"
                                  placeholder="Short description of the dish…"></textarea>
                    </div>

                    {{-- Price / MRP / Tag / T&C --}}
                    <div class="col-sm-3">
                        <label class="form-label small fw-semibold mb-1">Price (₹)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-success-subtle text-success-emphasis border-success-subtle">₹</span>
                            <input type="text" class="form-control border-success-subtle"
                                   wire:model="editingItem.price" placeholder="299">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small fw-semibold mb-1">Original Price (MRP)</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="text" class="form-control"
                                   wire:model="editingItem.op" placeholder="399">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small fw-semibold mb-1">Badge / Tag</label>
                        <input type="text" class="form-control"
                               wire:model="editingItem.tag" placeholder="Bestseller">
                    </div>
                    <div class="col-sm-3">
                        <label class="form-label small fw-semibold mb-1">Small Print</label>
                        <select class="form-select" wire:model="editingItem.tc">
                            <option value="">— none —</option>
                            <option>per serving</option>
                            <option>per piece</option>
                            <option>per plate</option>
                            <option>per bowl</option>
                            <option>per glass</option>
                            <option>per portion</option>
                            <option>per kg</option>
                            <option>per 100g</option>
                            <option>per litre</option>
                            <option>onwards</option>
                        </select>
                    </div>

                </div>
            </div>
            <div class="modal-footer border-top py-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-success btn-sm px-4"
                        onclick="window.__menuSaveItem()"
                        wire:loading.attr="disabled">
                    <span wire:loading wire:target="saveMenuItemModal">
                        <span class="spinner-border spinner-border-sm me-1"></span>
                    </span>
                    <i class="mdi mdi-content-save-outline me-1" wire:loading.remove wire:target="saveMenuItemModal"></i>
                    Save Item
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        if (window.__menuModalListenerRegistered) { return; }
        window.__menuModalListenerRegistered = true;

        function cleanBackdrops() {
            document.querySelectorAll('.modal-backdrop').forEach(function (el) { el.remove(); });
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');
        }

        // Instantly hide a modal without Bootstrap's fade transition,
        // then clean backdrops — must finish before Livewire morphs the DOM.
        function hideInstant(id) {
            var el = document.getElementById(id);
            if (el) {
                el.classList.remove('show');
                el.style.display = 'none';
                el.setAttribute('aria-hidden', 'true');
                el.removeAttribute('aria-modal');
                el.removeAttribute('role');
                var inst = bootstrap.Modal.getInstance(el);
                if (inst) { inst.dispose(); }
            }
            cleanBackdrops();
        }

        window.__menuSaveItem = function () {
            var comp = window.__menuWireComp;
            if (!comp) { console.error('Menu Livewire component not found'); return; }
            var file = window.__menuItemFile;
            if (file) {
                comp.$upload(
                    'uploads.menuItemEdit.product_image',
                    file,
                    function () { window.__menuItemFile = null; comp.$call('saveMenuItemModal'); },
                    function (err) { console.error('Upload failed', err); }
                );
            } else {
                comp.$call('saveMenuItemModal');
            }
        };

        document.addEventListener('open-menu-item-modal', function (e) {
            var wireId = e.detail && e.detail.wireId ? e.detail.wireId : null;
            window.__menuWireComp = wireId ? Livewire.find(wireId) : null;
            window.__menuItemFile = null;
            var fi = document.getElementById('menuItemFileInput');
            if (fi) { fi.value = ''; }
            cleanBackdrops();
            bootstrap.Modal.getOrCreateInstance(document.getElementById('menuItemModal')).show();
        });

        document.addEventListener('hide-menu-item-modal',  function () { hideInstant('menuItemModal'); });
        document.addEventListener('hide-category-modal',   function () { hideInstant('addMenuCategoryModal'); });
    })();
</script>
