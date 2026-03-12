{{-- doctor-clinic-template/specializations.blade.php --}}
@php
$items = $form;
$fields = [
    ['key'=>'name', 'label'=>'Name', 'type'=>'text', 'span'=>'col-md-4', 'placeholder'=>'Cardiology'],
    ['key'=>'tone', 'label'=>'Colour Tone', 'type'=>'select', 'span'=>'col-md-5', 'options'=>[
        ['value'=>'chip-red',    'label'=>'Red — Cardiology / Emergency'],
        ['value'=>'chip-blue',   'label'=>'Blue — Orthopaedics'],
        ['value'=>'chip-teal',   'label'=>'Teal — Neurology'],
        ['value'=>'chip-green',  'label'=>'Green — General Medicine'],
        ['value'=>'chip-purple', 'label'=>'Purple — Oncology'],
        ['value'=>'chip-amber',  'label'=>'Amber — ENT'],
    ]],
    ['key'=>'icon', 'label'=>'Icon', 'type'=>'select', 'span'=>'col-md-3', 'options'=>[
        ['value'=>'pulse',       'label'=>'Pulse / Heart Monitor'],
        ['value'=>'heart',       'label'=>'Heart'],
        ['value'=>'respiratory', 'label'=>'Respiratory / Lungs'],
        ['value'=>'home',        'label'=>'Home Visit'],
        ['value'=>'search',      'label'=>'Search / Diagnosis'],
        ['value'=>'preventive',  'label'=>'Preventive Care'],
        ['value'=>'info',        'label'=>'Info'],
    ]],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'dr-specs',
    'itemLabel'   => 'Specialization',
    'tableFields' => ['name', 'tone'],
    'fields'      => $fields,
])
