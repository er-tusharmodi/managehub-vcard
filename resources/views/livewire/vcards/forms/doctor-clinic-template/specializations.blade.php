{{-- doctor-clinic-template/specializations.blade.php --}}
@php
$items = $form;
$fields = [
    ['key'=>'name', 'label'=>'Name', 'type'=>'text', 'span'=>'col-md-4', 'placeholder'=>'Cardiology'],
    ['key'=>'tone', 'label'=>'Colour Tone', 'type'=>'select', 'span'=>'col-md-5', 'options'=>[
        ['key'=>'#e74c3c','label'=>'Red — Cardiology / Emergency'],
        ['key'=>'#0369a1','label'=>'Blue — Orthopaedics'],
        ['key'=>'#0d9488','label'=>'Teal — Neurology'],
        ['key'=>'#15803d','label'=>'Green — General Medicine'],
        ['key'=>'#7c3aed','label'=>'Purple — Oncology'],
        ['key'=>'#b45309','label'=>'Amber — ENT'],
        ['key'=>'#0284c7','label'=>'Sky — Paediatrics'],
        ['key'=>'#db2777','label'=>'Pink — Gynaecology'],
        ['key'=>'#9333ea','label'=>'Violet — Psychiatry'],
        ['key'=>'#64748b','label'=>'Grey — Other'],
    ]],
    ['key'=>'icon', 'label'=>'Icon', 'type'=>'datalist', 'span'=>'col-md-3', 'options'=>['🫀','🧠','🦴','🦷','👁️','👂','🫁','🩸','💊','🩺','🔬','🧬','💪','🤰','👶']],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'dr-specs',
    'itemLabel'   => 'Specialization',
    'tableFields' => ['name', 'tone'],
    'fields'      => $fields,
])
