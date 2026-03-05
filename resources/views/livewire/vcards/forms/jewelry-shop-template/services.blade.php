{-- jewelry-shop-template/services.blade.php --}
@php
$items = $form;
$fields = [
    ['key'=>'icon', 'label'=>'Icon', 'type'=>'text', 'span'=>'col-md-2', 'placeholder'=>'💎'],
    ['key'=>'name', 'label'=>'Service Name', 'type'=>'text', 'span'=>'col-md-4', 'placeholder'=>'Custom Design'],
    ['key'=>'desc', 'label'=>'Description', 'type'=>'textarea', 'span'=>'col-12', 'placeholder'=>'Bespoke jewellery crafted to your vision', 'rows'=>2],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'jwl-svcs',
    'itemLabel'   => 'Service',
    'tableFields' => ['name', 'desc'],
    'fields'      => $fields,
])
