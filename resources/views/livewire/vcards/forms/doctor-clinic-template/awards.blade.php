{{-- doctor-clinic-template/awards.blade.php --}}
@php
$items = $form;
$fields = [
    ['key'=>'name', 'label'=>'Award Title', 'type'=>'text', 'span'=>'col-md-6', 'placeholder'=>'Best Cardiologist Award 2023'],
    ['key'=>'desc', 'label'=>'Description', 'type'=>'textarea', 'span'=>'col-12', 'placeholder'=>'Awarded by Indian Medical Association…', 'rows'=>2],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'dr-awards',
    'itemLabel'   => 'Award',
    'tableFields' => ['name', 'desc'],
    'fields'      => $fields,
])
