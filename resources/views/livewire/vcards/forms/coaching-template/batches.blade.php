{-- coaching-template/batches.blade.php --}
@php
$items = $form['items'] ?? [];
$fields = [
    ['key'=>'name', 'label'=>'Batch Name', 'type'=>'text', 'span'=>'col-md-6', 'placeholder'=>'UPSC 2025 Morning'],
    ['key'=>'status', 'label'=>'Status', 'type'=>'text', 'span'=>'col-md-3', 'placeholder'=>'Open'],
    ['key'=>'seats', 'label'=>'Seats', 'type'=>'text', 'span'=>'col-md-3', 'placeholder'=>'12 left'],
    ['key'=>'feeHint', 'label'=>'Fee Hint', 'type'=>'text', 'span'=>'col-md-4', 'placeholder'=>'₹45,000/yr'],
    ['key'=>'ctaText', 'label'=>'CTA Button Text', 'type'=>'text', 'span'=>'col-md-4', 'placeholder'=>'Enroll Now'],
    ['key'=>'enquiryText', 'label'=>'WhatsApp Enquiry', 'type'=>'textarea', 'span'=>'col-12', 'placeholder'=>'I want to enquire about this batch', 'rows'=>2],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => 'items',
    'modelBase'   => 'form.items',
    'sectionKey'  => 'coaching-batches',
    'itemLabel'   => 'Batch',
    'tableFields' => ['name', 'status', 'seats'],
    'fields'      => $fields,
])
