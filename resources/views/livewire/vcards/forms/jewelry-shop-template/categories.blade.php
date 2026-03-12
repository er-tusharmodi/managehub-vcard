{{-- jewelry-shop-template/categories.blade.php --}}
@php
$items = $form;
$fields = [
    ['key'=>'label', 'label'=>'Label', 'type'=>'text', 'span'=>'col-md-9', 'placeholder'=>'Gold Jewellery'],
    ['key'=>'active', 'label'=>'Active', 'type'=>'toggle', 'span'=>'col-md-3', 'placeholder'=>'Show in filter'],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'jwl-cats',
    'itemLabel'   => 'Category',
    'tableFields' => ['label'],
    'fields'      => $fields,
])
