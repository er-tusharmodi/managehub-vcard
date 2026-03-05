{{--
 | coaching-template/whyChoose.blade.php
 | Why Choose Us cards: {title(static), items:[{title, description, gradient, iconClass}]}
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-star-circle-outline me-1"></i>Why Choose Us — Cards
    </h6>
</div>

@php $items = $form['items'] ?? []; @endphp
@foreach($items as $i => $card)
<div class="col-12 mb-2" wire:key="whychoose-{{ $i }}">
    <div class="border rounded-3 p-3 bg-light">
        <div class="row g-2">
            <div class="col-sm-6">
                <label class="form-label small mb-1 fw-semibold">Card Title</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.title"
                       placeholder="Expert Guidance">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Icon Class</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.iconClass"
                       placeholder="bi-mortarboard">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Gradient</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.gradient"
                       placeholder="from-blue-500 to-purple-600">
            </div>
            <div class="col-12">
                <label class="form-label small mb-1 fw-semibold">Description</label>
                <textarea class="form-control form-control-sm" rows="2"
                          wire:model="form.items.{{ $i }}.description"
                          placeholder="Describe this benefit..."></textarea>
            </div>
        </div>
    </div>
</div>
@endforeach
