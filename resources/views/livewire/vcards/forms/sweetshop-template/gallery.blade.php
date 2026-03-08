{{-- sweetshop-template/gallery.blade.php — [{image, caption}] --}}
@php
$items = $form ?? [];
$fields = [
    ['key' => 'image',   'label' => 'Photo',   'type' => 'image', 'span' => 'col-md-4'],
    ['key' => 'caption', 'label' => 'Caption', 'type' => 'text',  'span' => 'col-12',  'placeholder' => 'e.g. Kaju Katli'],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'ss-gallery',
    'itemLabel'   => 'Photo',
    'tableFields' => ['image'],
    'fields'      => $fields,
])
