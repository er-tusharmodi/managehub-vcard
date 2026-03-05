{{--
 | coaching-template/materials.blade.php
 | Study materials: items[{name, detail, iconClass, iconColor, bg, enquiryText}]
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-book-multiple-outline me-1"></i>Study Materials
    </h6>
</div>

@php $items = $form['items'] ?? []; @endphp
@foreach($items as $i => $mat)
<div class="col-12 mb-2" wire:key="mat-{{ $i }}">
    <div class="border rounded-3 p-2 bg-light">
        <div class="row g-2">
            <div class="col-sm-5">
                <label class="form-label small mb-1 fw-semibold">Material Name</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.name" placeholder="Current Affairs Booklet">
            </div>
            <div class="col-sm-3">
                <label class="form-label small mb-1 fw-semibold">Icon Class</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.iconClass" placeholder="bi-journal-text">
            </div>
            <div class="col-sm-4">
                <label class="form-label small mb-1 fw-semibold">Enquiry Text</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.enquiryText" placeholder="I want Current Affairs Booklet">
            </div>
            <div class="col-12">
                <label class="form-label small mb-1 fw-semibold">Detail</label>
                <input type="text" class="form-control form-control-sm"
                       wire:model="form.items.{{ $i }}.detail" placeholder="Monthly edition, 200+ pages">
            </div>
        </div>
    </div>
</div>
@endforeach
