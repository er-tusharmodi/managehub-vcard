{{-- coaching-template/modes.blade.php --}}
@php
$items = $form['items'] ?? [];
$iconOptions = [
    ['value'=>'bi-building',          'label'=>'Building / Classroom'],
    ['value'=>'bi-laptop',            'label'=>'Laptop / Online'],
    ['value'=>'bi-camera-video',      'label'=>'Camera / Video Class'],
    ['value'=>'bi-broadcast',         'label'=>'Broadcast / Live'],
    ['value'=>'bi-phone',             'label'=>'Phone / Mobile'],
    ['value'=>'bi-display',           'label'=>'Display / Screen'],
    ['value'=>'bi-people',            'label'=>'People / Group Study'],
    ['value'=>'bi-person-video2',     'label'=>'Person Video / Mentor'],
    ['value'=>'bi-mortarboard',       'label'=>'Mortarboard / Academic'],
    ['value'=>'bi-journal-check',     'label'=>'Journal / Self Study'],
    ['value'=>'bi-wifi',              'label'=>'WiFi / Online'],
    ['value'=>'bi-house',             'label'=>'House / Home Study'],
    ['value'=>'bi-geo-alt',           'label'=>'Location / In-Centre'],
    ['value'=>'bi-calendar-check',    'label'=>'Calendar / Weekend'],
    ['value'=>'bi-clock',             'label'=>'Clock / Evening Batch'],
    ['value'=>'bi-moon-stars',        'label'=>'Moon / Night Batch'],
    ['value'=>'bi-sun',               'label'=>'Sun / Morning Batch'],
];
$fields = [
    ['key'=>'name',        'label'=>'Mode Name',   'type'=>'text',     'span'=>'col-md-4', 'placeholder'=>'Online Live'],
    ['key'=>'description', 'label'=>'Description', 'type'=>'textarea', 'span'=>'col-12',   'placeholder'=>'Live interactive classes via Zoom…', 'rows'=>2],
    ['key'=>'iconClass',   'label'=>'Icon',        'type'=>'select',   'span'=>'col-md-4', 'options'=>$iconOptions],
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
