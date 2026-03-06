{{-- mens-salon-template/packages.blade.php --}}
@php
$items = $form ?? [];
$fields = [
    ['key'=>'name',       'label'=>'Package Name', 'type'=>'text', 'span'=>'col-md-5', 'placeholder'=>'Premium Grooming'],
    ['key'=>'badge',      'label'=>'Badge Text',   'type'=>'text', 'span'=>'col-md-3', 'placeholder'=>'Best Value'],
    ['key'=>'badgeClass', 'label'=>'Badge Class',  'type'=>'text', 'span'=>'col-md-4', 'placeholder'=>'bg-warning'],
    ['key'=>'price',      'label'=>'Price',        'type'=>'text', 'prefix'=>'₹', 'span'=>'col-md-3', 'placeholder'=>'499'],
    ['key'=>'old',        'label'=>'Old Price',    'type'=>'text', 'prefix'=>'₹', 'span'=>'col-md-3', 'placeholder'=>'699'],
    ['key'=>'save',       'label'=>'Save Amount',  'type'=>'text', 'span'=>'col-md-3', 'placeholder'=>'Save ₹200'],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'ms-pkgs',
    'itemLabel'   => 'Package',
    'tableFields' => ['name', 'price', 'old'],
    'fields'      => $fields,
])
