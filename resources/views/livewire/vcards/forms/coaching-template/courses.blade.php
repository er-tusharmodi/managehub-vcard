{-- coaching-template/courses.blade.php --}
@php
$items = $form['items'] ?? [];
$fields = [
    ['key'=>'imageUrl', 'label'=>'Image', 'type'=>'image', 'span'=>'col-md-3'],
    ['key'=>'name', 'label'=>'Course Name', 'type'=>'text', 'span'=>'col-md-5', 'placeholder'=>'UPSC Foundation'],
    ['key'=>'tag', 'label'=>'Tag', 'type'=>'text', 'span'=>'col-md-4', 'placeholder'=>'IAS/IPS'],
    ['key'=>'iconClass', 'label'=>'Icon Class', 'type'=>'text', 'span'=>'col-md-6', 'placeholder'=>'bi-book'],
    ['key'=>'enquiryText', 'label'=>'WhatsApp Enquiry', 'type'=>'textarea', 'span'=>'col-12', 'placeholder'=>'I want to enquire about this course', 'rows'=>2],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => 'items',
    'modelBase'   => 'form.items',
    'sectionKey'  => 'coaching-courses',
    'itemLabel'   => 'Course',
    'tableFields' => ['imageUrl', 'name', 'tag'],
    'fields'      => $fields,
])
