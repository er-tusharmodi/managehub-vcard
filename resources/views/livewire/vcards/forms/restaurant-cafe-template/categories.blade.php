{{-- restaurant-cafe-template/categories.blade.php --}}
{{-- Items: [{key, label}] — key is auto-derived (slug of label), never shown to user --}}
@php $cats = is_array($form) ? array_values($form) : []; @endphp

<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-1" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-shape-outline me-1"></i>Menu Categories
    </h6>
    <small class="text-muted">
        These become the filter tabs on the menu section.
        <strong>Key is auto-generated</strong> from the label — no need to type it manually.
    </small>
</div>

<div class="col-12">
    <div class="border rounded-3 overflow-hidden shadow-sm">
        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between px-3 py-2"
             style="background:linear-gradient(90deg,#f0fdf4,#dcfce7);">
            <span class="fw-semibold d-flex align-items-center gap-2" style="font-size:.85rem;color:#14532d;">
                <i class="mdi mdi-tag-multiple-outline"></i>
                Categories
                <span class="badge" style="background:#dcfce7;color:#166534;font-size:.7rem;">
                    {{ count($cats) }}
                </span>
            </span>
        </div>

        <div class="p-3">
            {{-- Existing category pills --}}
            @if(count($cats) > 0)
                <div class="d-flex flex-wrap gap-2 mb-3" data-sort-path="">
                    @foreach($cats as $ci => $cat)
                    <div class="input-group input-group-sm" style="width:auto;max-width:220px;" wire:key="rc-cat-{{ $ci }}">
                        <span class="input-group-text drag-handle"
                              style="cursor:grab;background:#dcfce7;border-color:#bbf7d0;padding:0 6px;">
                            <i class="mdi mdi-drag-vertical" style="font-size:13px;color:#16a34a;"></i>
                        </span>
                        <span class="input-group-text px-2"
                              style="background:#f0fdf4;border-color:#bbf7d0;">
                            <i class="mdi mdi-tag-outline" style="font-size:13px;color:#16a34a;"></i>
                        </span>
                        <input type="text"
                               class="form-control form-control-sm"
                               style="border-color:#bbf7d0;min-width:80px;"
                               wire:model="form.{{ $ci }}.label"
                               placeholder="e.g. Starters">
                        <button type="button"
                                class="btn btn-outline-danger btn-sm"
                                onclick="showConfirmToast('Remove this category?', function(){ window.Livewire.find('{{ $_instance->getId() }}').call('removeRowWithConfirm', {{ $ci }}, '') })"
                                title="Remove">
                            <i class="mdi mdi-close" style="font-size:12px;"></i>
                        </button>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted small mb-3">
                    <i class="mdi mdi-information-outline me-1"></i>No categories yet. Add some below.
                </p>
            @endif

            {{-- Add new category — key auto-generated from label --}}
            <div x-data="{
                    syncKey() {
                        const raw = $refs.labelInput.value;
                        const slug = raw.toLowerCase()
                                       .replace(/[\s_]+/g, '-')
                                       .replace(/[^a-z0-9\u0900-\u097f-]/g, '')
                                       .replace(/^-+|-+$/g, '');
                        $wire.set('newItem.key', slug || '');
                    }
                }">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="background:#f0fdf4;border-color:#bbf7d0;">
                        <i class="mdi mdi-plus" style="color:#16a34a;"></i>
                    </span>
                    <input type="text"
                           class="form-control form-control-sm"
                           x-ref="labelInput"
                           wire:model="newItem.label"
                           placeholder="e.g. Starters, Mains, Desserts…"
                           x-on:input="syncKey()"
                           x-on:keydown.enter.prevent="syncKey(); $nextTick(() => $wire.call('addRowAndSave', '', ['key', 'label']))">
                    {{-- hidden key field kept in sync --}}
                    <input type="hidden" wire:model="newItem.key">
                    <button type="button"
                            class="btn btn-sm"
                            style="background:#16a34a;color:#fff;border-color:#16a34a;"
                            x-on:click="syncKey(); $nextTick(() => $wire.call('addRowAndSave', '', ['key', 'label']))">
                        <i class="mdi mdi-plus me-1"></i>Add Category
                    </button>
                </div>
            </div>
            <p class="text-muted mt-2 mb-0" style="font-size:.78rem;">
                <i class="mdi mdi-information-outline me-1"></i>
                Save after adding. Then assign categories to menu items in the <strong>MENU</strong> section.
            </p>
        </div>
    </div>
</div>
