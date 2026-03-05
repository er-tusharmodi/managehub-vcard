{{--
 | jewelry-shop-template/purity.blade.php
 | Purity grades + hallmark info: items[{karat}], hallmark{emoji, title, separator, text}
--}}
<div class="col-12 mb-2">
    <h6 class="fw-semibold text-muted text-uppercase mb-0" style="font-size:.72rem;letter-spacing:.07em;">
        <i class="mdi mdi-seal me-1"></i>Purity &amp; Hallmark
    </h6>
</div>

{{-- Hallmark block --}}
@php $hallmark = $form['hallmark'] ?? []; @endphp
@if(!empty($hallmark))
<div class="col-12 mb-2">
    <label class="form-label fw-semibold text-muted small text-uppercase">Hallmark Banner</label>
</div>
<div class="col-lg-2 mb-3">
    <label class="form-label small fw-semibold mb-1">Emoji</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.hallmark.emoji" placeholder="✅">
</div>
<div class="col-lg-4 mb-3">
    <label class="form-label small fw-semibold mb-1">Title</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.hallmark.title" placeholder="BIS Hallmarked Jewellery">
</div>
<div class="col-lg-2 mb-3">
    <label class="form-label small fw-semibold mb-1">Separator</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.hallmark.separator" placeholder="·">
</div>
<div class="col-lg-4 mb-3">
    <label class="form-label small fw-semibold mb-1">Sub-text</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.hallmark.text" placeholder="Certified Pure Gold">
</div>
@endif

{{-- Purity items --}}
@php $items = $form['items'] ?? []; @endphp
@if(!empty($items))
<div class="col-12 mb-1">
    <label class="form-label fw-semibold text-muted small text-uppercase">Karat Options</label>
</div>
@foreach($items as $i => $purity)
<div class="col-lg-3 mb-2" wire:key="purity-{{ $i }}">
    <label class="form-label small mb-1 fw-semibold">Karat {{ $i+1 }}</label>
    <input type="text" class="form-control form-control-sm"
           wire:model="form.items.{{ $i }}.karat" placeholder="22K">
</div>
@endforeach
@endif
