{-- electronics-shop-template/repairServices.blade.php --}
@php
$items = $form;
$fields = [
    ['key'=>'product_image', 'label'=>'Photo', 'type'=>'image', 'span'=>'col-md-3'],
    ['key'=>'name', 'label'=>'Service Name', 'type'=>'text', 'span'=>'col-md-6', 'placeholder'=>'Screen Replacement'],
    ['key'=>'sub', 'label'=>'Sub-title', 'type'=>'text', 'span'=>'col-md-6', 'placeholder'=>'iPhone 14 Pro / 15 Pro'],
    ['key'=>'price', 'label'=>'Price', 'type'=>'text', 'span'=>'col-md-3', 'placeholder'=>'₹3,500'],
    ['key'=>'icon', 'label'=>'Icon', 'type'=>'text', 'span'=>'col-md-3', 'placeholder'=>'🛠️'],
    ['key'=>'query', 'label'=>'WA Query', 'type'=>'textarea', 'span'=>'col-12', 'placeholder'=>'I need screen replacement for…', 'rows'=>2],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'elec-repair',
    'itemLabel'   => 'Repair Service',
    'tableFields' => ['name', 'price'],
    'fields'      => $fields,
])
