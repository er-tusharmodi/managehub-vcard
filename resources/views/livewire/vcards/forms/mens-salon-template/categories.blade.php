{{-- mens-salon-template/categories.blade.php --}}
@php
$items = $form ?? [];
$fields = [
    ['key'=>'label', 'label'=>'Label', 'type'=>'text', 'span'=>'col-12', 'placeholder'=>'Hair Care'],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'ms-cats',
    'itemLabel'   => 'Category',
    'tableFields' => ['key', 'label'],
    'fields'      => $fields,
])
