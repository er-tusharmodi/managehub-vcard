{{--
 | coaching-template/batches.blade.php
 | Batch cards: items[{status, name, feeHint, meta[], seats, ctaText, enquiryText}]
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-calendar-clock me-1"></i>Batches
    </h6>
</div>

@php $items = $form['items'] ?? []; @endphp
@foreach($items as $i => $batch)
<div class="col-12 mb-3" wire:key="batch-{{ $i }}">
    <div class="border rounded-3 p-3 bg-light">
        <div class="row g-2">
            <div class="col-sm-6">
                <label class="form-label small mb-1 fw-semibold">Batch Name</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.name" placeholder="New Batch 2025">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Status</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.status" placeholder="Upcoming">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Seats</label>
                <input type="number" class="form-control form-control-sm" min="0"
                       wire:model="form.items.{{ $i }}.seats" placeholder="60">
            </div>
            <div class="col-sm-6">
                <label class="form-label small mb-1 fw-semibold">Fee Hint</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.feeHint" placeholder="₹8,500 / month">
            </div>
            <div class="col-sm-6">
                <label class="form-label small mb-1 fw-semibold">CTA Text</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.ctaText" placeholder="Join This Batch">
            </div>
            <div class="col-12">
                <label class="form-label small mb-1 fw-semibold">Enquiry Text (WA pre-fill)</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.enquiryText"
                       placeholder="I want to join the New Batch 2025">
            </div>
            {{-- Meta chips --}}
            @php $meta = $batch['meta'] ?? []; @endphp
            @if(!empty($meta))
            <div class="col-12">
                <label class="form-label small mb-1 fw-semibold text-muted">Meta Info Chips</label>
                <div class="row g-1">
                    @foreach($meta as $mi => $metaItem)
                    <div class="col-auto">
                        <input type="text" class="form-control form-control-sm" style="width:140px;"
                               wire:model="form.items.{{ $i }}.meta.{{ $mi }}"
                               placeholder="Starts Jan 2025">
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endforeach
