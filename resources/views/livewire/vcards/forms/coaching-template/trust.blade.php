{{-- coaching-template/trust.blade.php --}}
@php
$items = $form['items'] ?? [];
$fields = [
    ['key'=>'iconClass', 'label'=>'Icon Class', 'type'=>'text', 'span'=>'col-md-4', 'placeholder'=>'bi-patch-check-fill'],
    ['key'=>'text', 'label'=>'Text', 'type'=>'text', 'span'=>'col-md-8', 'placeholder'=>'500+ successful selections'],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => 'items',
    'modelBase'   => 'form.items',
    'sectionKey'  => 'coaching-trust',
    'itemLabel'   => 'Trust Point',
    'tableFields' => ['text'],
    'fields'      => $fields,
])
