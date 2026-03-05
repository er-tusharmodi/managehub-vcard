{-- restaurant-cafe-template/offers.blade.php --}
@php
$items = $form ?? [];
$fields = [
    ['key'=>'product_image', 'label'=>'Photo', 'type'=>'image', 'span'=>'col-md-3'],
    ['key'=>'title', 'label'=>'Title', 'type'=>'text', 'span'=>'col-md-5', 'placeholder'=>'Weekend Pizza Deal'],
    ['key'=>'desc', 'label'=>'Description', 'type'=>'textarea', 'span'=>'col-12', 'placeholder'=>'Any 2 large pizzas for ₹599', 'rows'=>2],
    ['key'=>'tag', 'label'=>'Tag', 'type'=>'text', 'span'=>'col-md-4', 'placeholder'=>'Sat–Sun Only'],
    ['key'=>'icon', 'label'=>'Icon', 'type'=>'text', 'span'=>'col-md-3', 'placeholder'=>'🍕'],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'rc-offers',
    'itemLabel'   => 'Offer',
    'tableFields' => ['product_image', 'title', 'tag'],
    'fields'      => $fields,
])
