{-- sweetshop-template/services.blade.php --}
@php
$items = $form ?? [];
$fields = [
    ['key'=>'image', 'label'=>'Photo', 'type'=>'image', 'span'=>'col-md-3'],
    ['key'=>'name', 'label'=>'Name', 'type'=>'text', 'span'=>'col-md-5', 'placeholder'=>'Custom Order'],
    ['key'=>'description', 'label'=>'Description', 'type'=>'textarea', 'span'=>'col-12', 'placeholder'=>'Personalised sweets for events & gifts', 'rows'=>2],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'ss-svcs',
    'itemLabel'   => 'Service',
    'tableFields' => ['name', 'description'],
    'fields'      => $fields,
])
