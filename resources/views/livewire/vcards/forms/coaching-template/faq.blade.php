{{--
 | coaching-template/faq.blade.php
 | FAQ accordion: items[{question, answer}]
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-help-circle-outline me-1"></i>Frequently Asked Questions
    </h6>
</div>

@php $items = $form['items'] ?? []; @endphp
@foreach($items as $i => $faq)
<div class="col-12 mb-2" wire:key="faq-{{ $i }}">
    <div class="border rounded-3 p-2 bg-light">
        <div class="row g-2">
            <div class="col-12">
                <label class="form-label small mb-1 fw-semibold">Question</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.question"
                       placeholder="How long is the UPSC course?">
            </div>
            <div class="col-12">
                <label class="form-label small mb-1 fw-semibold">Answer</label>
                <textarea class="form-control form-control-sm" rows="2"
                          wire:model="form.items.{{ $i }}.answer"
                          placeholder="The course duration is 12 months..."></textarea>
            </div>
        </div>
    </div>
</div>
@endforeach
