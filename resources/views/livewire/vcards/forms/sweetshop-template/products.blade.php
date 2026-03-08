{{-- sweetshop-template/products.blade.php --}}
@php
$items = $form ?? [];
$fields = [
    ['key'=>'product_image', 'label'=>'Photo', 'type'=>'image', 'span'=>'col-md-3'],
    ['key'=>'name', 'label'=>'Name', 'type'=>'text', 'span'=>'col-md-5', 'placeholder'=>'Kaju Katli'],
    ['key'=>'desc', 'label'=>'Description', 'type'=>'textarea', 'span'=>'col-12', 'placeholder'=>'Premium cashew fudge with silver leaf', 'rows'=>2],
    ['key'=>'category_key', 'label'=>'Category', 'type'=>'select', 'span'=>'col-md-4', 'options'=>$categoryOptions ?? []],
    ['key'=>'price',    'label'=>'Price (₹/kg)', 'type'=>'text',  'prefix'=>'₹', 'span'=>'col-md-3', 'placeholder'=>'800'],
    ['key'=>'per',      'label'=>'Per Unit',      'type'=>'text',  'span'=>'col-md-3', 'placeholder'=>'kg'],
    ['key'=>'tag',      'label'=>'Tag',           'type'=>'text',  'span'=>'col-md-4', 'placeholder'=>'Bestseller'],
    ['key'=>'tagColor', 'label'=>'Tag Color',     'type'=>'color', 'span'=>'col-md-4', 'placeholder'=>'#e74c3c'],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'ss-prods',
    'itemLabel'   => 'Sweet / Product',
    'tableFields' => ['product_image', 'name', 'price'],
    'fields'      => $fields,
])
