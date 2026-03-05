{{--
 | coaching-template/modes.blade.php
 | Learning modes: items[{name, description, gradient, iconClass}]
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-laptop me-1"></i>Learning Modes
    </h6>
</div>

@php $items = $form['items'] ?? []; @endphp
@foreach($items as $i => $mode)
<div class="col-12 mb-2" wire:key="mode-{{ $i }}">
    <div class="border rounded-3 p-2 bg-light">
        <div class="row g-2">
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Mode Name</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.name" placeholder="Online Live">
            </div>
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Icon Class</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.iconClass" placeholder="bi-camera-video">
            </div>
            <div class="col-12">
                <label class="form-label small mb-1 fw-semibold">Description</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.description"
                       placeholder="Live interactive sessions from home">
            </div>
        </div>
    </div>
</div>
@endforeach
