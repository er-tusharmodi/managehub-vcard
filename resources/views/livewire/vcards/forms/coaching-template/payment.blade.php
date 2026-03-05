{-- coaching-template/payment.blade.php --}
@php
$items = $form['items'] ?? [];
$fields = [
    ['key'=>'name', 'label'=>'Method Name', 'type'=>'text', 'span'=>'col-md-4', 'placeholder'=>'UPI / GPay'],
    ['key'=>'detail', 'label'=>'Detail', 'type'=>'text', 'span'=>'col-md-5', 'placeholder'=>'Scan QR or pay to 9999@upi'],
    ['key'=>'iconClass', 'label'=>'Icon Class', 'type'=>'text', 'span'=>'col-md-3', 'placeholder'=>'bi-qr-code'],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => 'items',
    'modelBase'   => 'form.items',
    'sectionKey'  => 'coaching-payment',
    'itemLabel'   => 'Payment Method',
    'tableFields' => ['name', 'detail'],
    'fields'      => $fields,
])
