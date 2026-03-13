{{-- sweetshop-template/location.blade.php — {line1, line2, mapButtonLabel} --}}
<div class="col-12 mb-3">
    <div class="d-flex align-items-center gap-2 pb-2 border-bottom">
        <span class="avatar-xs flex-shrink-0">
            <span class="avatar-title rounded-circle bg-soft-primary text-primary font-size-18">
                <i class="mdi mdi-map-marker"></i>
            </span>
        </span>
        <div>
            <h6 class="mb-0 fw-semibold">Location Details</h6>
            <small class="text-muted">Shop address and map button label</small>
        </div>
    </div>
</div>
<div class="col-12">
    <div class="card border shadow-sm" style="border-radius:.75rem;">
        <div class="card-body p-3">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label small fw-semibold mb-1">Address Line 1</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light"><i class="mdi mdi-map-marker-outline text-muted"></i></span>
                        <input type="text" class="form-control"
                               wire:model="form.line1"
                               placeholder="Shop No. 5, Sadar Bazaar">
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label small fw-semibold mb-1">Address Line 2</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light"><i class="mdi mdi-city-variant-outline text-muted"></i></span>
                        <input type="text" class="form-control"
                               wire:model="form.line2"
                               placeholder="Agra, Uttar Pradesh — 282003">
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-semibold mb-1">Map Button Label</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light"><i class="mdi mdi-navigation-variant-outline text-muted"></i></span>
                        <input type="text" class="form-control"
                               wire:model="form.mapButtonLabel"
                               placeholder="Open in Maps">
                    </div>
                    <small class="text-muted">Label shown on the "Get Directions" button.</small>
                </div>
            </div>
        </div>
    </div>
</div>

