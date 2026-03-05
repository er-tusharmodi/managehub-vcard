{{-- mens-salon-template/profile.blade.php — {ownerTag, name, role, tagline} --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-account-tie me-1"></i>Owner Profile
    </h6>
</div>
<div class="col-md-4">
    <label class="form-label small mb-1 fw-semibold">Owner Tag</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.ownerTag" placeholder="e.g. Owner & Master Barber">
</div>
<div class="col-md-4">
    <label class="form-label small mb-1 fw-semibold">Name</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.name" placeholder="Rajesh Kumar">
</div>
<div class="col-md-4">
    <label class="form-label small mb-1 fw-semibold">Role / Title</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.role" placeholder="Master Barber · 12 yrs exp">
</div>
<div class="col-12">
    <label class="form-label small mb-1 fw-semibold">Tagline</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.tagline" placeholder="Precision cuts. Classic styles. Modern grooming.">
</div>
