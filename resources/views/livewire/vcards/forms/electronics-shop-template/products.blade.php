{{-- electronics-shop-template/products.blade.php --}}
@php
$items = $form;
$fields = [
    ['key'=>'product_image', 'label'=>'Photo', 'type'=>'image', 'span'=>'col-md-3'],
    ['key'=>'name', 'label'=>'Product Name', 'type'=>'text', 'span'=>'col-md-6', 'placeholder'=>'Samsung Galaxy S25'],
    ['key'=>'brand', 'label'=>'Brand', 'type'=>'text', 'span'=>'col-md-3', 'placeholder'=>'Samsung'],
    ['key'=>'spec', 'label'=>'Spec', 'type'=>'text', 'span'=>'col-md-6', 'placeholder'=>'6.7" AMOLED, 256GB, 5G'],
    ['key'=>'category_key', 'label'=>'Category', 'type'=>'select', 'span'=>'col-md-3', 'options'=>$categoryOptions ?? []],
    ['key'=>'price',    'label'=>'Price',     'type'=>'text',  'prefix'=>'₹', 'span'=>'col-md-3', 'placeholder'=>'79,999'],
    ['key'=>'oldPrice', 'label'=>'Old Price', 'type'=>'text',  'prefix'=>'₹', 'span'=>'col-md-3', 'placeholder'=>'89,999'],
    ['key'=>'tag',      'label'=>'Tag',       'type'=>'text',  'span'=>'col-md-4', 'placeholder'=>'New Launch'],
    ['key'=>'tagColor', 'label'=>'Tag Color', 'type'=>'color', 'span'=>'col-md-4', 'placeholder'=>'#e74c3c'],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'elec-prods',
    'itemLabel'   => 'Product',
    'tableFields' => ['product_image', 'name', 'price'],
    'fields'      => $fields,
])
