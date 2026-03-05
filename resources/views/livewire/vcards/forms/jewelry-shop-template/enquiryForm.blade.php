{{-- jewelry-shop-template/enquiryForm.blade.php — {defaultEmail, defaultCategory, defaultBudget, defaultMessage, categories[]} --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-email-edit-outline me-1"></i>Enquiry Form Defaults
    </h6>
</div>
<div class="col-md-6">
    <label class="form-label small mb-1 fw-semibold">Default Email</label>
    <input type="email" class="form-control form-control-sm"
           wire:model="form.defaultEmail" placeholder="enquiry@shop.com">
</div>
<div class="col-md-6">
    <label class="form-label small mb-1 fw-semibold">Default Category</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.defaultCategory" placeholder="gold">
</div>
<div class="col-md-6">
    <label class="form-label small mb-1 fw-semibold">Default Budget</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.defaultBudget" placeholder="50000">
</div>
<div class="col-12">
    <label class="form-label small mb-1 fw-semibold">Default Message</label>
    <textarea class="form-control form-control-sm" rows="2"
              wire:model="form.defaultMessage" placeholder="I am interested in…"></textarea>
</div>
<div class="col-12 mt-2">
    <label class="form-label small mb-1 fw-semibold">Category Options (for dropdown)</label>
    <p class="small text-muted mb-1">Each entry: <code>key | Label</code> — one per category row</p>
    @if(is_array($form['categories'] ?? null))
    @foreach(($form['categories'] ?? []) as $ci => $cat)
    <div class="input-group input-group-sm mb-1" wire:key="jenqcat-{{ $ci }}">
        <input type="text" class="form-control form-control-sm"
               wire:model="form.categories.{{ $ci }}.key" placeholder="gold">
        <input type="text" class="form-control form-control-sm"
               wire:model="form.categories.{{ $ci }}.label" placeholder="Gold">
    </div>
    @endforeach
    @endif
</div>
