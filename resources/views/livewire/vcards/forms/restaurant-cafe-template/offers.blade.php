{{-- restaurant-cafe-template/offers.blade.php --}}
{{-- Read-only table. Add / Edit via modal. --}}

{{-- Section header --}}
<div class="col-12 mb-2">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
                <i class="mdi mdi-tag-multiple-outline me-1"></i>Special Offers
                <span class="badge bg-warning-subtle text-warning-emphasis ms-1">{{ count($form ?? []) }}</span>
            </h6>
        </div>
        <button type="button" class="btn btn-warning btn-sm px-3"
                wire:click="openOfferModal()">
            <i class="mdi mdi-plus me-1"></i>Add Offer
        </button>
    </div>
</div>

{{-- Table --}}
<div class="col-12">
    <div class="card border shadow-sm overflow-hidden" style="border-radius:.75rem;">
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0" style="font-size:.83rem;">
                <thead>
                    <tr style="background:linear-gradient(90deg,#fffbeb,#fef3c7);border-bottom:2px solid #fde68a;">
                        <th style="width:28px;"></th>
                        <th class="px-2 py-2 text-muted fw-semibold" style="width:28px;font-size:.68rem;">#</th>
                        <th class="py-2 text-muted fw-semibold" style="width:44px;font-size:.68rem;">Icon</th>
                        <th class="py-2 text-muted fw-semibold" style="min-width:160px;font-size:.68rem;">Title</th>
                        <th class="py-2 text-muted fw-semibold" style="font-size:.68rem;">Description</th>
                        <th class="py-2 text-muted fw-semibold" style="width:110px;font-size:.68rem;">Tag</th>
                        <th style="width:68px;"></th>
                    </tr>
                </thead>
                <tbody data-sort-path="">
                    @forelse(($form ?? []) as $oi => $offer)
                    <tr wire:key="offer-row-{{ $oi }}" class="border-bottom">
                        <td class="drag-handle text-center text-muted ps-2" style="cursor:grab;width:28px;"><i class="mdi mdi-drag-vertical"></i></td>
                        <td class="px-2 text-muted fw-semibold" style="font-size:.7rem;">{{ $oi + 1 }}</td>
                        <td class="py-1 text-center" style="font-size:1.3rem;">{{ $offer['icon'] ?? '🏷️' }}</td>
                        <td class="py-1 fw-semibold">{{ $offer['title'] ?: '—' }}</td>
                        <td class="py-1 text-muted" style="font-size:.78rem;">
                            {{ \Str::limit($offer['desc'] ?? '', 70) ?: '—' }}
                        </td>
                        <td class="py-1">
                            @if(!empty($offer['tag']))
                                <span class="badge bg-warning-subtle text-warning-emphasis" style="font-size:.65rem;">{{ $offer['tag'] }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td class="py-1 text-center" style="white-space:nowrap;">
                            <button type="button"
                                    class="btn btn-sm btn-outline-primary p-0 rounded-circle me-1"
                                    style="width:26px;height:26px;line-height:1;"
                                    wire:click="openOfferModal({{ $oi }})">
                                <i class="mdi mdi-pencil-outline" style="font-size:12px;"></i>
                            </button>
                            <button type="button"
                                    class="btn btn-sm btn-outline-danger p-0 rounded-circle"
                                    style="width:26px;height:26px;line-height:1;"
                                    x-on:click="showConfirmToast('Remove this offer?', () => $wire.removeRowWithConfirm({{ $oi }}, ''), '{{ $offer['title'] ?? '' }}')">
                                <i class="mdi mdi-delete" style="font-size:12px;"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-4 text-center text-muted">
                            <i class="mdi mdi-tag-off-outline d-block" style="font-size:2rem;opacity:.35;"></i>
                            <small>No offers yet. Click <strong>Add Offer</strong> to create one.</small>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════════════════ --}}
{{-- OFFER MODAL (Add / Edit)                                               --}}
{{-- ══════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="offerModal" tabindex="-1" aria-labelledby="offerModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
        <div class="modal-content border-0 shadow">
            <div class="modal-header py-3"
                 style="background:linear-gradient(90deg,#fffbeb,#fef3c7);border-bottom:2px solid #fde68a;">
                <h5 class="modal-title fw-semibold d-flex align-items-center gap-2" id="offerModalLabel">
                    <i class="mdi mdi-tag-outline" style="color:#92400e;"></i>
                    <span style="color:#92400e;">
                        {{ $editingIndex !== null ? 'Edit Offer' : 'Add Offer' }}
                    </span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">

                    {{-- Icon Picker --}}
                    <div class="col-12">
                        <label class="form-label small fw-semibold mb-1">Icon / Emoji</label>
                        <div x-data="{
                            open: false,
                            val: {{ json_encode($editingItem['icon'] ?? '') }},
                            svgIcons: ['brunch','candle','coffee','cake'],
                            svgLabels: {'brunch':'Brunch','candle':'Candle Dinner','coffee':'Coffee','cake':'Cake'},
                            emojis: [
                                {e:'🔥',l:'Hot Deal'},{e:'🎉',l:'Celebration'},{e:'🍕',l:'Pizza'},{e:'⭐',l:'Special'},
                                {e:'🎁',l:'Gift'},{e:'🏷️',l:'Tag'},{e:'💥',l:'Flash Sale'},{e:'🥳',l:'Party'},
                                {e:'🍔',l:'Burger'},{e:'🌮',l:'Tacos'},{e:'🍜',l:'Noodles'},{e:'🍣',l:'Sushi'},
                                {e:'🥗',l:'Salad'},{e:'🍛',l:'Curry'},{e:'🥩',l:'Steak'},{e:'🍗',l:'Chicken'},
                                {e:'🍰',l:'Dessert'},{e:'🧁',l:'Cupcake'},{e:'🍩',l:'Donut'},{e:'🍫',l:'Choco'},
                                {e:'☕',l:'Coffee'},{e:'🥤',l:'Drink'},{e:'🍺',l:'Beer'},{e:'🍷',l:'Wine'},
                                {e:'🧋',l:'Bubble Tea'},{e:'💯',l:'100%'},{e:'🌟',l:'Star'},{e:'✨',l:'Sparkle'},
                                {e:'🤑',l:'Money'},{e:'👨‍🍳',l:'Chef'},{e:'🍽️',l:'Dining'},{e:'🎊',l:'Confetti'}
                            ],
                            pick(v){ this.val = v; this.open = false; $wire.set('editingItem.icon', v, false); }
                        }" class="position-relative" @click.outside="open=false">

                            {{-- Trigger button --}}
                            <button type="button"
                                    class="d-flex align-items-center gap-2 w-100 form-control text-start"
                                    style="min-height:44px;cursor:pointer;"
                                    @click="open=!open">
                                <span x-text="val || '—'" style="font-size:1.4rem;line-height:1;"></span>
                                <span class="text-muted small flex-grow-1" x-show="!val">Pick an icon…</span>
                                <i class="mdi mdi-chevron-down ms-auto text-muted"></i>
                            </button>

                            {{-- Dropdown panel --}}
                            <div x-show="open" x-transition
                                 class="position-absolute start-0 end-0 bg-white border rounded-3 shadow-lg p-2 mt-1"
                                 style="z-index:1070;top:100%;max-height:280px;overflow-y:auto;">

                                {{-- SVG named icons --}}
                                <p class="text-muted mb-1 px-1" style="font-size:.65rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;">Template SVG Icons</p>
                                <div class="d-flex flex-wrap gap-1 mb-2">
                                    <template x-for="k in svgIcons" :key="k">
                                        <button type="button"
                                                class="btn btn-sm d-flex flex-column align-items-center justify-content-center gap-1 p-1"
                                                style="width:60px;min-height:52px;border:1.5px solid #e5e7eb;border-radius:8px;font-size:.6rem;"
                                                :style="val===k ? 'border-color:#f59e0b;background:#fffbeb;' : ''"
                                                @click="pick(k)">
                                            <span style="font-size:.9rem;">🎨</span>
                                            <span class="text-muted" x-text="svgLabels[k]" style="font-size:.58rem;line-height:1.1;text-align:center;word-break:break-word;"></span>
                                        </button>
                                    </template>
                                </div>

                                {{-- Emoji icons --}}
                                <p class="text-muted mb-1 px-1" style="font-size:.65rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;">Emojis</p>
                                <div class="d-flex flex-wrap gap-1">
                                    <template x-for="item in emojis" :key="item.e">
                                        <button type="button"
                                                class="btn btn-sm d-flex flex-column align-items-center justify-content-center gap-1 p-1"
                                                style="width:52px;min-height:50px;border:1.5px solid #e5e7eb;border-radius:8px;"
                                                :style="val===item.e ? 'border-color:#f59e0b;background:#fffbeb;' : ''"
                                                @click="pick(item.e)">
                                            <span style="font-size:1.25rem;line-height:1;" x-text="item.e"></span>
                                            <span class="text-muted" x-text="item.l" style="font-size:.57rem;line-height:1.1;text-align:center;word-break:break-word;"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Title --}}
                    <div class="col-sm-8">
                        <label class="form-label small fw-semibold mb-1">Title</label>
                        <input type="text" class="form-control"
                               wire:model.blur="editingItem.title"
                               placeholder="Weekend Pizza Deal">
                    </div>

                    {{-- Tag --}}
                    <div class="col-12">
                        <label class="form-label small fw-semibold mb-1">
                            Tag <span class="text-muted fw-normal">(badge shown on card)</span>
                        </label>
                        <input type="text" class="form-control"
                               wire:model.blur="editingItem.tag"
                               placeholder="Sat–Sun Only">
                    </div>

                    {{-- Description --}}
                    <div class="col-12">
                        <label class="form-label small fw-semibold mb-1">Description</label>
                        <textarea class="form-control" rows="3"
                                  wire:model.blur="editingItem.desc"
                                  placeholder="Any 2 large pizzas for ₹599"></textarea>
                    </div>

                </div>
            </div>
            <div class="modal-footer border-top py-2">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-warning btn-sm px-4" onclick="window.__offerSaveItem()">
                    <i class="mdi mdi-content-save-outline me-1"></i>
                    Save Offer
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        if (window.__offerModalListenerRegistered) { return; }
        window.__offerModalListenerRegistered = true;

        function cleanBackdrops() {
            document.querySelectorAll('.modal-backdrop').forEach(function (el) { el.remove(); });
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
            document.body.style.removeProperty('padding-right');
        }

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

        window.__offerSaveItem = function () {
            var comp = window.__offerWireComp;
            if (!comp) { console.error('Offer Livewire component not found'); return; }
            // Call saveOfferModal first — PHP saves and dispatches 'hide-offer-modal'
            // which then triggers hideInstant. This ensures Alpine @@entangle data is
            // fully synced before the modal is destroyed.
            comp.$call('saveOfferModal');
        };

        document.addEventListener('open-offer-modal', function (e) {
            var wireId = e.detail && e.detail.wireId ? e.detail.wireId : null;
            window.__offerWireComp = wireId ? Livewire.find(wireId) : null;
            cleanBackdrops();
            bootstrap.Modal.getOrCreateInstance(document.getElementById('offerModal')).show();
        });

        document.addEventListener('hide-offer-modal', function () { hideInstant('offerModal'); });
    })();
</script>
