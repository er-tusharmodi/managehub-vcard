{{-- jewelry-shop-template/enquiryForm.blade.php --}}

<div class="col-12 mb-3">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-email-edit-outline me-1"></i>Enquiry Form Settings
    </h6>
</div>

{{-- Placeholders --}}
<div class="col-md-6">
    <label class="form-label small mb-1 fw-semibold">Name Placeholder</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.namePlaceholder" placeholder="Your Full Name *">
</div>
<div class="col-md-6">
    <label class="form-label small mb-1 fw-semibold">Phone Placeholder</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.phonePlaceholder" placeholder="Mobile Number *">
</div>
<div class="col-md-6">
    <label class="form-label small mb-1 fw-semibold">Email Placeholder</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.emailPlaceholder" placeholder="Email Address (Optional)">
</div>
<div class="col-md-6">
    <label class="form-label small mb-1 fw-semibold">Category Placeholder</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.categoryPlaceholder" placeholder="Select Category of Interest">
</div>
<div class="col-md-6">
    <label class="form-label small mb-1 fw-semibold">Budget Placeholder</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.budgetPlaceholder" placeholder="Approximate Budget">
</div>
<div class="col-md-6">
    <label class="form-label small mb-1 fw-semibold">Message Placeholder</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.messagePlaceholder" placeholder="Describe what you're looking for...">
</div>

{{-- Defaults --}}
<div class="col-12 mt-3 mb-1">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.68rem;letter-spacing:.07em;">
        <i class="mdi mdi-tune-vertical-variant me-1"></i>Fallback Defaults (used when user skips a field)
    </h6>
</div>
<div class="col-md-3">
    <label class="form-label small mb-1 fw-semibold">Default Email</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.defaultEmail" placeholder="—">
</div>
<div class="col-md-3">
    <label class="form-label small mb-1 fw-semibold">Default Category</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.defaultCategory" placeholder="Not specified">
</div>
<div class="col-md-3">
    <label class="form-label small mb-1 fw-semibold">Default Budget</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.defaultBudget" placeholder="Not specified">
</div>
<div class="col-md-3">
    <label class="form-label small mb-1 fw-semibold">Default Message</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.defaultMessage" placeholder="No additional message">
</div>

{{-- Submit Label --}}
<div class="col-md-6 mt-1">
    <label class="form-label small mb-1 fw-semibold">Submit Button Label</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.submitLabel" placeholder="Send Enquiry via WhatsApp">
</div>

{{-- ── Category Options ── --}}
<div class="col-12 mt-4">
    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom mb-2">
        <span class="fw-semibold text-uppercase text-muted" style="font-size:.7rem;letter-spacing:.07em;">
            <i class="mdi mdi-format-list-bulleted me-1"></i>Category Options (dropdown)
            <span class="badge bg-secondary-subtle text-secondary ms-1">{{ count($form['categories'] ?? []) }}</span>
        </span>
    </div>

    @php $cats = $form['categories'] ?? []; @endphp

    @if(!empty($cats))
        @foreach($cats as $ci => $cat)
        <div class="mb-1" wire:key="jenqcat-{{ $ci }}">
            <div class="input-group input-group-sm">
                <span class="input-group-text text-muted" style="font-size:.75rem;min-width:28px;">{{ $ci + 1 }}</span>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.categories.{{ $ci }}"
                       placeholder="e.g. Gold Jewellery">
                <button type="button" class="btn btn-sm btn-outline-danger"
                        wire:click="removeRowWithConfirm({{ $ci }}, 'categories')"
                        wire:confirm="Remove this category option?"
                        title="Remove">
                    <i class="mdi mdi-delete" style="font-size:13px;"></i>
                </button>
            </div>
        </div>
        @endforeach
    @else
        <div class="text-center py-3 rounded-2 mb-2"
             style="background:#f8fafc;border:2px dashed #cbd5e1;">
            <p class="small text-muted mb-0">No category options yet</p>
        </div>
    @endif

    {{-- Add new category --}}
    <div class="d-flex gap-2 align-items-center mt-2">
        <input type="text" class="form-control form-control-sm"
               wire:model="newItem.cat"
               wire:keydown.enter="addStringAndSave('categories', 'cat')"
               placeholder="New category (e.g. Diamond Jewellery)">
        <button type="button" class="btn btn-sm btn-primary flex-shrink-0"
                wire:click="addStringAndSave('categories', 'cat')">
            <i class="mdi mdi-plus me-1"></i>Add
        </button>
    </div>
</div>
