{{-- doctor-clinic-template/conditions.blade.php --}}
@php
$items = $form;
$fields = [
    ['key'=>'image', 'label'=>'Image', 'type'=>'image', 'span'=>'col-md-3'],
    ['key'=>'name', 'label'=>'Condition Name', 'type'=>'text', 'span'=>'col-md-5', 'placeholder'=>'Hypertension'],
    ['key'=>'desc', 'label'=>'Description', 'type'=>'textarea', 'span'=>'col-12', 'placeholder'=>'Commonly treated with lifestyle changes and medication…', 'rows'=>2],
    ['key'=>'query', 'label'=>'WA Query Text', 'type'=>'text', 'span'=>'col-12', 'placeholder'=>'I want to consult for Hypertension'],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'dr-conditions',
    'itemLabel'   => 'Condition',
    'tableFields' => ['image', 'name', 'desc'],
    'fields'      => $fields,
])
