{{-- sweetshop-template/paymentMethods.blade.php — [{type, name, detail}] --}}
@php
$items = $form ?? [];
$fields = [
    ['key' => 'type',   'label' => 'Type',   'type' => 'text', 'span' => 'col-md-3', 'placeholder' => 'upi / bank / cash'],
    ['key' => 'name',   'label' => 'Name',   'type' => 'text', 'span' => 'col-md-4', 'placeholder' => 'UPI / Bank Transfer / Cash'],
    ['key' => 'detail', 'label' => 'Detail', 'type' => 'text', 'span' => 'col-md-5', 'placeholder' => 'UPI ID: shop@okaxis'],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'ss-payments',
    'itemLabel'   => 'Payment Method',
    'tableFields' => ['name', 'detail'],
    'fields'      => $fields,
])