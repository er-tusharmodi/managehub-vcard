{-- mens-salon-template/services.blade.php --}
@php
$items = $form ?? [];
$fields = [
    ['key'=>'product_image', 'label'=>'Photo', 'type'=>'image', 'span'=>'col-md-3'],
    ['key'=>'name', 'label'=>'Service Name', 'type'=>'text', 'span'=>'col-md-5', 'placeholder'=>'Haircut (Regular)'],
    ['key'=>'desc', 'label'=>'Description', 'type'=>'textarea', 'span'=>'col-12', 'placeholder'=>'Classic scissor/clipper cut', 'rows'=>2],
    ['key'=>'price', 'label'=>'Price', 'type'=>'text', 'span'=>'col-md-3', 'placeholder'=>'₹149'],
    ['key'=>'dur', 'label'=>'Duration', 'type'=>'text', 'span'=>'col-md-3', 'placeholder'=>'20 min'],
    ['key'=>'icon', 'label'=>'Icon', 'type'=>'text', 'span'=>'col-md-3', 'placeholder'=>'✂️'],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'ms-svcs',
    'itemLabel'   => 'Service',
    'tableFields' => ['name', 'price', 'dur'],
    'fields'      => $fields,
])
