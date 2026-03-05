{-- coaching-template/modes.blade.php --}
@php
$items = $form['items'] ?? [];
$fields = [
    ['key'=>'name', 'label'=>'Mode Name', 'type'=>'text', 'span'=>'col-md-4', 'placeholder'=>'Online Live'],
    ['key'=>'description', 'label'=>'Description', 'type'=>'textarea', 'span'=>'col-12', 'placeholder'=>'Live interactive classes via Zoom…', 'rows'=>2],
    ['key'=>'iconClass', 'label'=>'Icon Class', 'type'=>'text', 'span'=>'col-md-4', 'placeholder'=>'bi-camera-video'],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => 'items',
    'modelBase'   => 'form.items',
    'sectionKey'  => 'coaching-modes',
    'itemLabel'   => 'Mode',
    'tableFields' => ['name', 'description'],
    'fields'      => $fields,
])
