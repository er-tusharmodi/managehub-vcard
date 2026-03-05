{{-- minimart-template/profile.blade.php — {name, role, bio} --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-store me-1"></i>Store Profile
    </h6>
</div>
<div class="col-md-6">
    <label class="form-label small mb-1 fw-semibold">Store / Owner Name</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.name" placeholder="Arjun Fresh Mart">
</div>
<div class="col-md-6">
    <label class="form-label small mb-1 fw-semibold">Role / Designation</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.role" placeholder="Owner & Manager">
</div>
<div class="col-12">
    <label class="form-label small mb-1 fw-semibold">Bio / About</label>
    <textarea class="form-control form-control-sm" rows="2"
              wire:model="form.bio" placeholder="Your neighbourhood grocery store since 2010…"></textarea>
</div>
