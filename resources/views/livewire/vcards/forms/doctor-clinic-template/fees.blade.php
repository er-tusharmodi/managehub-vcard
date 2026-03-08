{{-- doctor-clinic-template/fees.blade.php --}}
@php
$items = $form['items'] ?? [];
$fields = [
    ['key'=>'name', 'label'=>'Consultation Type', 'type'=>'text', 'span'=>'col-md-5', 'placeholder'=>'General OPD'],
    ['key'=>'amount', 'label'=>'Fee', 'type'=>'text', 'span'=>'col-md-3', 'placeholder'=>'500', 'prefix'=>'₹'],
    ['key'=>'oldAmount', 'label'=>'Original Fee', 'type'=>'text', 'span'=>'col-md-3', 'placeholder'=>'700', 'prefix'=>'₹'],
    ['key'=>'note', 'label'=>'Note', 'type'=>'text', 'span'=>'col-md-6', 'placeholder'=>'Includes follow-up within 3 days'],
    ['key'=>'icon', 'label'=>'Icon', 'type'=>'datalist', 'span'=>'col-md-3', 'options'=>['🏥','💊','🩺','💉','🩻','✅','🔬','🧬','💪','👨‍⚕️','🩸','🦷','👁️','🫀','🫁']],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => 'items',
    'modelBase'   => 'form.items',
    'sectionKey'  => 'dr-fees',
    'itemLabel'   => 'Fee Item',
    'tableFields' => ['name', 'amount'],
    'fields'      => $fields,
])
