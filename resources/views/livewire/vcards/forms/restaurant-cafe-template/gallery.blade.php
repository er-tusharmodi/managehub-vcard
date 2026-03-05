{-- restaurant-cafe-template/gallery.blade.php --}
@php
$items = $form ?? [];
$fields = [
    ['key'=>'image', 'label'=>'Photo', 'type'=>'image', 'span'=>'col-md-4'],
    ['key'=>'caption', 'label'=>'Caption', 'type'=>'text', 'span'=>'col-12', 'placeholder'=>'Chef special — grilled sea bass'],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'rc-gallery',
    'itemLabel'   => 'Photo',
    'tableFields' => ['image', 'caption'],
    'fields'      => $fields,
])
