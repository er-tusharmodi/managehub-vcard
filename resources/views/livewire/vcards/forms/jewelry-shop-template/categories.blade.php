{{-- jewelry-shop-template/categories.blade.php --}}
@php
$items = $form;
$fields = [
    ['key'=>'key', 'label'=>'Key', 'type'=>'text', 'span'=>'col-md-4', 'placeholder'=>'gold'],
    ['key'=>'label', 'label'=>'Label', 'type'=>'text', 'span'=>'col-md-5', 'placeholder'=>'Gold Jewellery'],
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
