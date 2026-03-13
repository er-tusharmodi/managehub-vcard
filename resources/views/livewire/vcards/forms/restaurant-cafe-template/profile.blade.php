{{-- restaurant-cafe-template/profile.blade.php --}}
{{-- Profile: cuisineTags = category tags shown under restaurant name AND as menu tab labels. --}}

<div class="col-12 mb-3">
    <h6 class="fw-semibold text-muted text-uppercase mb-1" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-store me-1"></i>Profile Categories
    </h6>
    <small class="text-muted">
        These tags appear below the restaurant name <strong>and</strong> become the category tabs
        in the Menu section. Add or rename them here — then manage menu items under each category
        in the <strong>Menu</strong> section.
    </small>
</div>

<div class="col-12">
    <div class="border rounded-3 overflow-hidden shadow-sm">
        <div class="d-flex align-items-center justify-content-between px-3 py-2"
             style="background:linear-gradient(90deg,#fff7ed,#fef3c7);">
            <span class="fw-semibold text-warning-emphasis d-flex align-items-center gap-2" style="font-size:.85rem;">
                <i class="mdi mdi-tag-multiple-outline"></i>
                Cuisine / Category Tags
                <span class="badge bg-warning-subtle text-warning-emphasis" style="font-size:.7rem;">
                    {{ is_array($form['cuisineTags'] ?? null) ? count($form['cuisineTags']) : 0 }}
                </span>
            </span>
        </div>

        <div class="p-3">
            @if(is_array($form['cuisineTags'] ?? null) && count($form['cuisineTags']) > 0)
                <div class="d-flex flex-wrap gap-2 mb-3" data-sort-path="cuisineTags">
                    @foreach($form['cuisineTags'] as $cti => $tag)
                    <div class="input-group input-group-sm" style="width:auto;max-width:220px;" wire:key="rc-ctag-{{ $cti }}">
                        <span class="input-group-text drag-handle" style="cursor:grab;background:#e5e7eb;border-color:#d1d5db;padding:0 6px;">
                            <i class="mdi mdi-drag-vertical" style="font-size:13px;color:#6b7280;"></i>
                        </span>
                        <span class="input-group-text bg-warning-subtle border-warning-subtle px-2">
                            <i class="mdi mdi-tag-outline text-warning-emphasis" style="font-size:13px;"></i>
                        </span>
                        <input type="text"
                               class="form-control form-control-sm border-warning-subtle"
                               wire:model="form.cuisineTags.{{ $cti }}"
                               placeholder="Italian"
                               style="min-width:80px;">
                        <button type="button"
                                class="btn btn-outline-danger btn-sm"
                                x-on:click="showConfirmToast('Remove cuisine tag?', () => $wire.removeRowWithConfirm({{ $cti }}, 'cuisineTags'), '{{ $tag }}')"
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

            <div class="input-group input-group-sm">
                <span class="input-group-text bg-warning-subtle border-warning-subtle">
                    <i class="mdi mdi-plus text-warning-emphasis"></i>
                </span>
                <input type="text"
                       class="form-control form-control-sm"
                       wire:model="newItem.cuisineTag"
                       placeholder="e.g. Italian, Café, Desserts…"
                       wire:keydown.enter.prevent="addStringAndSave('cuisineTags', 'cuisineTag')">
                <button type="button" class="btn btn-warning"
                        wire:click="addStringAndSave('cuisineTags', 'cuisineTag')">
                    <i class="mdi mdi-plus me-1"></i>Add Category
                </button>
            </div>
            <p class="text-muted mt-2 mb-0" style="font-size:.78rem;">
                <i class="mdi mdi-information-outline me-1"></i>
                Save after changes. Then go to <strong>Menu</strong> section to add items under each category.
            </p>
        </div>
    </div>
</div>
