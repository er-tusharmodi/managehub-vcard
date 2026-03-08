{{-- jewelry-shop-template/certifications.blade.php --}}
@php
$items = $form;
$fields = [
    ['key'=>'product_image', 'label'=>'Photo', 'type'=>'image', 'span'=>'col-md-3'],
    ['key'=>'emoji', 'label'=>'Emoji', 'type'=>'text', 'span'=>'col-md-2', 'placeholder'=>'🏅'],
    ['key'=>'name', 'label'=>'Name', 'type'=>'text', 'span'=>'col-md-4', 'placeholder'=>'BIS Hallmark Certified'],
    ['key'=>'sub', 'label'=>'Sub-text', 'type'=>'text', 'span'=>'col-md-5', 'placeholder'=>'Since 2005'],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'jwl-certs',
    'itemLabel'   => 'Certification',
    'tableFields' => ['name', 'sub'],
    'fields'      => $fields,
])
