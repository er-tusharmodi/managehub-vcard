{-- minimart-template/categories.blade.php --}
@php
$items = $form ?? [];
$fields = [
    ['key'=>'key', 'label'=>'Key', 'type'=>'text', 'span'=>'col-md-3', 'placeholder'=>'dairy'],
    ['key'=>'name', 'label'=>'Display Name', 'type'=>'text', 'span'=>'col-md-4', 'placeholder'=>'Dairy & Eggs'],
    ['key'=>'count', 'label'=>'Item Count', 'type'=>'text', 'span'=>'col-md-2', 'placeholder'=>'24'],
    ['key'=>'query', 'label'=>'Search Query', 'type'=>'text', 'span'=>'col-12', 'placeholder'=>'milk eggs cheese'],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'mm-cats',
    'itemLabel'   => 'Category',
    'tableFields' => ['name', 'count'],
    'fields'      => $fields,
])
