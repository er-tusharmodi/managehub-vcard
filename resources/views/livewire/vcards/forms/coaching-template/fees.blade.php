{-- coaching-template/fees.blade.php --}
@php
$items = $form['items'] ?? [];
$fields = [
    ['key'=>'name', 'label'=>'Course / Plan Name', 'type'=>'text', 'span'=>'col-md-6', 'placeholder'=>'UPSC Foundation'],
    ['key'=>'amount', 'label'=>'Price', 'type'=>'text', 'span'=>'col-md-3', 'placeholder'=>'₹45,000'],
    ['key'=>'oldAmount', 'label'=>'Original Price', 'type'=>'text', 'span'=>'col-md-3', 'placeholder'=>'₹60,000'],
    ['key'=>'note', 'label'=>'Note', 'type'=>'text', 'span'=>'col-md-6', 'placeholder'=>'Includes all study material'],
    ['key'=>'iconClass', 'label'=>'Icon Class', 'type'=>'text', 'span'=>'col-md-6', 'placeholder'=>'bi-star-fill'],
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
