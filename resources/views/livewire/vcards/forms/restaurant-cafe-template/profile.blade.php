{{-- restaurant-cafe-template/profile.blade.php --}}
{{-- Profile: cuisineTags (plain string array). Actions are vCard-side only — not editable here. --}}

<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-store me-1"></i>Profile
    </h6>
</div>

{{-- Cuisine / Category Tags --}}
<div class="col-12">
    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom mb-2">
        <span class="fw-semibold text-uppercase text-muted" style="font-size:.7rem;letter-spacing:.07em;">
            <i class="mdi mdi-tag-multiple-outline me-1"></i>Cuisine / Category Tags
            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ is_array($form['cuisineTags'] ?? null) ? count($form['cuisineTags']) : 0 }}</span>
        </span>
    </div>

    @if(is_array($form['cuisineTags'] ?? null))
        @foreach($form['cuisineTags'] as $cti => $tag)
        <div class="input-group input-group-sm mb-1" wire:key="rc-tag-{{ $cti }}">
            <span class="input-group-text text-muted" style="font-size:.75rem;">{{ $cti + 1 }}</span>
            <input type="text" class="form-control form-control-sm"
                   wire:model="form.cuisineTags.{{ $cti }}" placeholder="Italian">
            <button type="button" class="btn btn-outline-danger"
                    wire:click="removeRowWithConfirm({{ $cti }}, 'cuisineTags')"
                    wire:confirm="Remove this tag?">
                <i class="mdi mdi-delete-outline" style="font-size:13px;"></i>
            </button>
        </div>
        @endforeach
    @endif

    <div class="input-group input-group-sm mt-2">
        <input type="text" class="form-control form-control-sm"
               wire:model="newItem.cuisineTag" placeholder="Add a cuisine/category tag…"
               wire:keydown.enter.prevent="addStringAndSave('cuisineTags', 'cuisineTag')">
        <button type="button" class="btn btn-primary"
                wire:click="addStringAndSave('cuisineTags', 'cuisineTag')">
            <i class="mdi mdi-plus"></i> Add
        </button>
    </div>

    <p class="text-muted small mt-2 mb-0">
        <i class="mdi mdi-information-outline me-1"></i>
        Action buttons (Call, WhatsApp, Reserve…) are controlled by the vCard display settings — not editable here.
    </p>
</div>
