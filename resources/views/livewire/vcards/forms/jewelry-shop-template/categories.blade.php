{{--
 | jewelry-shop-template/categories.blade.php
 | Collection categories: [{key, label, active?}]
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-tag-multiple-outline me-1"></i>Collection Categories
    </h6>
</div>

@if(is_array($form))
@foreach($form as $i => $cat)
<div class="col-12 mb-2" wire:key="jcat-{{ $i }}">
    <div class="border rounded-3 p-2 bg-light">
        <div class="row g-2 align-items-center">
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Key (slug)</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.key" placeholder="gold">
            </div>
            <div class="col-sm-5">
                <label class="form-label small mb-1 fw-semibold">Display Label</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.{{ $i }}.label" placeholder="Gold Jewellery">
            </div>
            <div class="col-sm-3 d-flex align-items-end pb-1">
                <div class="form-check form-switch ms-1">
                    <input class="form-check-input" type="checkbox" role="switch"
                           id="jcat-active-{{ $i }}"
                           wire:model="form.{{ $i }}.active">
                    <label class="form-check-label small" for="jcat-active-{{ $i }}">Active</label>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
