{-- coaching-template/materials.blade.php --}
@php
$items = $form['items'] ?? [];
$fields = [
    ['key'=>'name', 'label'=>'Material Name', 'type'=>'text', 'span'=>'col-md-5', 'placeholder'=>'Current Affairs Digest'],
    ['key'=>'detail', 'label'=>'Detail', 'type'=>'text', 'span'=>'col-md-5', 'placeholder'=>'Monthly printed booklet'],
    ['key'=>'iconClass', 'label'=>'Icon Class', 'type'=>'text', 'span'=>'col-md-4', 'placeholder'=>'bi-journal-text'],
    ['key'=>'enquiryText', 'label'=>'WhatsApp Enquiry', 'type'=>'textarea', 'span'=>'col-12', 'placeholder'=>'I want to learn more about study materials', 'rows'=>2],
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
