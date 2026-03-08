{{-- coaching-template/whyChoose.blade.php --}}
@php
$items = $form['items'] ?? [];
$fields = [
    ['key'=>'name', 'label'=>'Reason Title', 'type'=>'text', 'span'=>'col-md-5', 'placeholder'=>'Expert Faculty'],
    ['key'=>'description', 'label'=>'Description', 'type'=>'textarea', 'span'=>'col-12', 'placeholder'=>'Our faculty has 15+ years average experience…', 'rows'=>2],
    ['key'=>'iconClass', 'label'=>'Icon Class', 'type'=>'text', 'span'=>'col-md-4', 'placeholder'=>'bi-person-check'],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => 'items',
    'modelBase'   => 'form.items',
    'sectionKey'  => 'coaching-why',
    'itemLabel'   => 'Reason',
    'tableFields' => ['name', 'description'],
    'fields'      => $fields,
])
