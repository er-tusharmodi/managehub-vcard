{{-- mens-salon-template/barbers.blade.php --}}
@php
$items = $form ?? [];
$fields = [
    ['key'=>'name', 'label'=>'Name', 'type'=>'text', 'span'=>'col-md-4', 'placeholder'=>'Rajesh Kumar'],
    ['key'=>'role', 'label'=>'Role', 'type'=>'text', 'span'=>'col-md-4', 'placeholder'=>'Senior Barber'],
    ['key'=>'exp', 'label'=>'Experience', 'type'=>'text', 'span'=>'col-md-4', 'placeholder'=>'10 yrs'],
    ['key'=>'avatar', 'label'=>'Initials', 'type'=>'text', 'span'=>'col-md-3', 'placeholder'=>'RK'],
    ['key'=>'gradient', 'label'=>'Gradient CSS', 'type'=>'text', 'span'=>'col-12', 'placeholder'=>'linear-gradient(135deg,#667eea,#764ba2)'],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'ms-barbers',
    'itemLabel'   => 'Barber',
    'tableFields' => ['name', 'role', 'exp'],
    'fields'      => $fields,
])
