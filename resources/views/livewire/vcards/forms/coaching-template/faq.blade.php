{-- coaching-template/faq.blade.php --}
@php
$items = $form['items'] ?? [];
$fields = [
    ['key'=>'question', 'label'=>'Question', 'type'=>'text', 'span'=>'col-12', 'placeholder'=>'What is the batch size?'],
    ['key'=>'answer', 'label'=>'Answer', 'type'=>'textarea', 'span'=>'col-12', 'placeholder'=>'Our batches have a maximum of 30 students…', 'rows'=>3],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => 'items',
    'modelBase'   => 'form.items',
    'sectionKey'  => 'coaching-faq',
    'itemLabel'   => 'FAQ',
    'tableFields' => ['question'],
    'fields'      => $fields,
])
