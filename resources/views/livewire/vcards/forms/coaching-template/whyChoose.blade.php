{{-- coaching-template/whyChoose.blade.php --}}
@php
$items = $form['items'] ?? [];
$iconOptions = [
    ['value'=>'bi-person-check',       'label'=>'Person Check'],
    ['value'=>'bi-award',              'label'=>'Award'],
    ['value'=>'bi-stars',              'label'=>'Stars'],
    ['value'=>'bi-shield-check',       'label'=>'Shield Check'],
    ['value'=>'bi-lightbulb',          'label'=>'Lightbulb'],
    ['value'=>'bi-graph-up-arrow',     'label'=>'Graph Up'],
    ['value'=>'bi-book',               'label'=>'Book'],
    ['value'=>'bi-headset',            'label'=>'Headset'],
    ['value'=>'bi-building',           'label'=>'Building'],
    ['value'=>'bi-mortarboard',        'label'=>'Mortarboard'],
    ['value'=>'bi-clipboard-check',    'label'=>'Clipboard Check'],
    ['value'=>'bi-chat-dots',          'label'=>'Chat / Support'],
    ['value'=>'bi-geo-alt',            'label'=>'Location'],
    ['value'=>'bi-cash-coin',          'label'=>'Cash / Fees'],
    ['value'=>'bi-check2-circle',      'label'=>'Check Circle'],
];
$fields = [
    ['key'=>'title',       'label'=>'Heading',     'type'=>'text',     'span'=>'col-md-5', 'placeholder'=>'Expert Faculty'],
    ['key'=>'description', 'label'=>'Description', 'type'=>'textarea', 'span'=>'col-12',   'placeholder'=>'Our faculty has 15+ years average experience…', 'rows'=>2],
    ['key'=>'iconClass',   'label'=>'Icon',        'type'=>'select',   'span'=>'col-md-4', 'options'=>$iconOptions],
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
