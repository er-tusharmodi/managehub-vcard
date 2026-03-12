{{-- doctor-clinic-template/payments.blade.php — [{name, detail, icon}] --}}
@php
$items = $form ?? [];
$fields = [
    ['key' => 'name',   'label' => 'Name',   'type' => 'text',     'span' => 'col-md-4', 'placeholder' => 'Cash / UPI / Card'],
    ['key' => 'detail', 'label' => 'Detail', 'type' => 'text',     'span' => 'col-md-5', 'placeholder' => 'GPay, PhonePe · +91 9XXXXXXXX'],
    ['key' => 'icon',   'label' => 'Icon',   'type' => 'select',   'span' => 'col-md-3', 'options' => [
        ['value' => 'cash',   'label' => 'Cash'],
        ['value' => 'card',   'label' => 'Card / UPI'],
        ['value' => 'shield', 'label' => 'Secure / Shield'],
    ]],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'dc-payments',
    'itemLabel'   => 'Payment Method',
    'tableFields' => ['name', 'detail'],
    'fields'      => $fields,
])
