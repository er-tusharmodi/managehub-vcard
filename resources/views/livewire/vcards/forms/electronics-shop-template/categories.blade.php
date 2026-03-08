{{-- electronics-shop-template/categories.blade.php --}}
@php
$items = $form;
$fields = [
    ['key'=>'key', 'label'=>'Key', 'type'=>'text', 'span'=>'col-md-3', 'placeholder'=>'mobiles'],
    ['key'=>'name', 'label'=>'Display Name', 'type'=>'text', 'span'=>'col-md-5', 'placeholder'=>'Mobile Phones'],
    ['key'=>'count', 'label'=>'Item Count', 'type'=>'text', 'span'=>'col-md-2', 'placeholder'=>'48'],
    ['key'=>'query', 'label'=>'Search Query', 'type'=>'text', 'span'=>'col-md-6', 'placeholder'=>'mobile phone smartphone'],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'elec-cats',
    'itemLabel'   => 'Category',
    'tableFields' => ['name', 'count'],
    'fields'      => $fields,
])
