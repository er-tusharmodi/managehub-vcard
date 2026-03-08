{{-- coaching-template/materials.blade.php --}}
@php
$items = $form['items'] ?? [];
$iconOptions = [
    ['value'=>'bi-journal-text',      'label'=>'Journal / Notes'],
    ['value'=>'bi-book',              'label'=>'Book'],
    ['value'=>'bi-book-half',         'label'=>'Book (open)'],
    ['value'=>'bi-file-earmark-text', 'label'=>'Document / PDF'],
    ['value'=>'bi-file-earmark-pdf',  'label'=>'PDF File'],
    ['value'=>'bi-file-earmark-richtext', 'label'=>'Rich Text File'],
    ['value'=>'bi-pencil-square',     'label'=>'Pencil / Practice'],
    ['value'=>'bi-pen',               'label'=>'Pen'],
    ['value'=>'bi-lightbulb',         'label'=>'Lightbulb / Tips'],
    ['value'=>'bi-lightning',         'label'=>'Lightning / Fast'],
    ['value'=>'bi-camera-video',      'label'=>'Video Lecture'],
    ['value'=>'bi-collection-play',   'label'=>'Video Collection'],
    ['value'=>'bi-newspaper',         'label'=>'Newspaper / Current Affairs'],
    ['value'=>'bi-clipboard2-data',   'label'=>'Clipboard / Test Series'],
    ['value'=>'bi-clipboard-check',   'label'=>'Clipboard / Solved Papers'],
    ['value'=>'bi-map',               'label'=>'Map / Topicwise'],
    ['value'=>'bi-diagram-3',         'label'=>'Mind Map'],
    ['value'=>'bi-question-circle',   'label'=>'Q&A / MCQ'],
    ['value'=>'bi-bar-chart-line',    'label'=>'Analytics / Progress'],
    ['value'=>'bi-award',             'label'=>'Award / Certificate'],
];
$fields = [
    ['key'=>'name',        'label'=>'Material Name',     'type'=>'text',     'span'=>'col-md-5', 'placeholder'=>'Current Affairs Digest'],
    ['key'=>'detail',      'label'=>'Detail',            'type'=>'text',     'span'=>'col-md-5', 'placeholder'=>'Monthly printed booklet'],
    ['key'=>'iconClass',   'label'=>'Icon',              'type'=>'select',   'span'=>'col-md-4', 'options'=>$iconOptions],
    ['key'=>'enquiryText', 'label'=>'WhatsApp Enquiry',  'type'=>'textarea', 'span'=>'col-12',   'placeholder'=>'I want to learn more about study materials', 'rows'=>2],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => 'items',
    'modelBase'   => 'form.items',
    'sectionKey'  => 'coaching-materials',
    'itemLabel'   => 'Material',
    'tableFields' => ['name', 'detail'],
    'fields'      => $fields,
])
