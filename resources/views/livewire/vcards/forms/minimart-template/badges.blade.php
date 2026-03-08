{{-- minimart-template/badges.blade.php --}}
@php
$items = $form ?? [];
$fields = [
    ['key'=>'icon', 'label'=>'Icon', 'type'=>'text', 'span'=>'col-md-2', 'placeholder'=>'🛒'],
    ['key'=>'text', 'label'=>'Text', 'type'=>'text', 'span'=>'col-md-8', 'placeholder'=>'Fresh Every Day'],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'mm-badges',
    'itemLabel'   => 'Badge',
    'tableFields' => ['icon', 'text'],
    'fields'      => $fields,
])
