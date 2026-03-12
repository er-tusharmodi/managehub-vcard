{{-- coaching-template/trust.blade.php --}}
@php
$items = $form['items'] ?? [];
$fields = [
    ['key'=>'text', 'label'=>'Text', 'type'=>'text', 'span'=>'col-12', 'placeholder'=>'500+ successful selections'],
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
