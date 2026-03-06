{-- jewelry-shop-template/collections.blade.php --}
@php
$items = $form;
$fields = [
    ['key'=>'product_image', 'label'=>'Photo', 'type'=>'image', 'span'=>'col-md-3'],
    ['key'=>'name', 'label'=>'Name', 'type'=>'text', 'span'=>'col-md-5', 'placeholder'=>'Bridal Kundan Set'],
    ['key'=>'metal', 'label'=>'Metal', 'type'=>'text', 'span'=>'col-md-4', 'placeholder'=>'22K Gold'],
    ['key'=>'desc', 'label'=>'Description', 'type'=>'textarea', 'span'=>'col-12', 'placeholder'=>'Exquisite bridal kundan jewellery with…', 'rows'=>2],
    ['key'=>'price',    'label'=>'Price',     'type'=>'text',  'prefix'=>'₹', 'span'=>'col-md-3', 'placeholder'=>'1,25,000'],
    ['key'=>'oldPrice', 'label'=>'Old Price', 'type'=>'text',  'prefix'=>'₹', 'span'=>'col-md-3', 'placeholder'=>'1,40,000'],
    ['key'=>'category_key', 'label'=>'Category', 'type'=>'select', 'span'=>'col-md-3', 'options'=>$categoryOptions ?? []],
    ['key'=>'tag',      'label'=>'Tag',       'type'=>'text',  'span'=>'col-md-4', 'placeholder'=>'Bestseller'],
    ['key'=>'tagColor', 'label'=>'Tag Color', 'type'=>'color', 'span'=>'col-md-4', 'placeholder'=>'#e67e22'],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'jwl-coll',
    'itemLabel'   => 'Collection',
    'tableFields' => ['product_image', 'name', 'price'],
    'fields'      => $fields,
])
