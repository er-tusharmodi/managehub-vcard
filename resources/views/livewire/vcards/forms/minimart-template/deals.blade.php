{-- minimart-template/deals.blade.php --}
@php
$items = $form ?? [];
$fields = [
    ['key'=>'badge', 'label'=>'Badge', 'type'=>'text', 'span'=>'col-md-3', 'placeholder'=>'20% OFF'],
    ['key'=>'name', 'label'=>'Deal Name', 'type'=>'text', 'span'=>'col-md-5', 'placeholder'=>'Weekend Combo'],
    ['key'=>'desc', 'label'=>'Description', 'type'=>'textarea', 'span'=>'col-12', 'placeholder'=>'Bread + Butter + Milk — ₹99', 'rows'=>2],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'mm-deals',
    'itemLabel'   => 'Deal',
    'tableFields' => ['badge', 'name', 'desc'],
    'fields'      => $fields,
])
