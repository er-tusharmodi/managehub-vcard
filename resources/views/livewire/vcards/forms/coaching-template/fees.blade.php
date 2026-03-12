{{-- coaching-template/fees.blade.php --}}
@php
$items = $form['items'] ?? [];
$fields = [
    ['key'=>'name',      'label'=>'Course / Plan Name', 'type'=>'text', 'span'=>'col-md-6', 'placeholder'=>'UPSC Foundation'],
    ['key'=>'amount',    'label'=>'Price',              'type'=>'text', 'prefix'=>'₹', 'span'=>'col-md-3', 'placeholder'=>'45,000'],
    ['key'=>'oldAmount', 'label'=>'Original Price',     'type'=>'text', 'prefix'=>'₹', 'span'=>'col-md-3', 'placeholder'=>'60,000'],
    ['key'=>'note',      'label'=>'Note',               'type'=>'text', 'span'=>'col-12',   'placeholder'=>'Includes all study material'],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => 'items',
    'modelBase'   => 'form.items',
    'sectionKey'  => 'coaching-fees',
    'itemLabel'   => 'Fee Item',
    'tableFields' => ['name', 'amount', 'oldAmount'],
    'fields'      => $fields,
])

{{-- Section-level EMI note --}}
<div class="col-12 mt-2">
    <label class="form-label fw-semibold" for="fees-emiNote">EMI / Instalment Note</label>
    <input type="text" id="fees-emiNote"
           class="form-control @error('form.emiNote') is-invalid @enderror"
           wire:model="form.emiNote"
           placeholder="EMI available from ₹3,750/month">
    @error('form.emiNote') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
