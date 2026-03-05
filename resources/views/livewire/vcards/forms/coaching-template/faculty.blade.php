{-- coaching-template/faculty.blade.php --}
@php
$items = $form['items'] ?? [];
$fields = [
    ['key'=>'imageUrl', 'label'=>'Photo', 'type'=>'image', 'span'=>'col-md-3'],
    ['key'=>'name', 'label'=>'Name', 'type'=>'text', 'span'=>'col-md-5', 'placeholder'=>'Dr. A. Kumar'],
    ['key'=>'initials', 'label'=>'Initials', 'type'=>'text', 'span'=>'col-md-2', 'placeholder'=>'AK'],
    ['key'=>'subject', 'label'=>'Subject', 'type'=>'text', 'span'=>'col-md-5', 'placeholder'=>'General Studies'],
    ['key'=>'experience', 'label'=>'Experience', 'type'=>'text', 'span'=>'col-md-4', 'placeholder'=>'12 Years'],
    ['key'=>'enquiryText', 'label'=>'WhatsApp Enquiry', 'type'=>'textarea', 'span'=>'col-12', 'placeholder'=>'I want to enquire about admissions', 'rows'=>2],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => 'items',
    'modelBase'   => 'form.items',
    'sectionKey'  => 'coaching-faculty',
    'itemLabel'   => 'Faculty Member',
    'tableFields' => ['imageUrl', 'name', 'subject'],
    'fields'      => $fields,
])
