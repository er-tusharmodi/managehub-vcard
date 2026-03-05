{{-- sweetshop-template/profile.blade.php — {name, role, bio} --}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-account me-1"></i>Shop Profile
    </h6>
</div>
<div class="col-md-6">
    <label class="form-label small mb-1 fw-semibold">Owner / Shop Name</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.name" placeholder="Mithai Wala – Suresh Gupta">
</div>
<div class="col-md-6">
    <label class="form-label small mb-1 fw-semibold">Role / Title</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.role" placeholder="Proprietor · 3rd Generation Halwai">
</div>
<div class="col-12">
    <label class="form-label small mb-1 fw-semibold">About</label>
    <textarea class="form-control form-control-sm" rows="2"
              wire:model="form.bio" placeholder="Serving authentic Indian sweets since 1965…"></textarea>
</div>
