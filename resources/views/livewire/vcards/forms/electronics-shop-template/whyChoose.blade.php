{{-- electronics-shop-template/whyChoose.blade.php --}}
@php
$items = is_array($form) ? $form : [];
$fields = [
    ['key'=>'text',  'label'=>'Feature Text',   'type'=>'text',     'span'=>'col-12',  'placeholder'=>'100% Genuine Products'],
    ['key'=>'tone',  'label'=>'Color Tone',      'type'=>'select',   'span'=>'col-md-4',
     'options'=>[
        ['key'=>'blue',   'label'=>'Blue'],
        ['key'=>'green',  'label'=>'Green'],
        ['key'=>'orange', 'label'=>'Orange'],
        ['key'=>'red',    'label'=>'Red'],
        ['key'=>'purple', 'label'=>'Purple'],
        ['key'=>'amber',  'label'=>'Amber'],
     ]],
    ['key'=>'icon',  'label'=>'Icon',            'type'=>'datalist', 'span'=>'col-md-4', 'placeholder'=>'shield',
     'options'=>['shield','truck','clock','price','chat','refresh','check','star','bolt','tools','wifi','sim','award','tag','box','headphone','phone','dollar']],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'elec-whychoose',
    'itemLabel'   => 'Feature',
    'tableFields' => ['text', 'tone', 'icon'],
    'fields'      => $fields,
])
