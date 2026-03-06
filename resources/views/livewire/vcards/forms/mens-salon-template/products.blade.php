{-- mens-salon-template/products.blade.php --}
@php
$items = $form ?? [];
$fields = [
    ['key'=>'product_image', 'label'=>'Photo', 'type'=>'image', 'span'=>'col-md-3'],
    ['key'=>'name', 'label'=>'Product Name', 'type'=>'text', 'span'=>'col-md-5', 'placeholder'=>'Beard Growth Oil'],
    ['key'=>'desc', 'label'=>'Description', 'type'=>'textarea', 'span'=>'col-12', 'placeholder'=>'Nourishes and strengthens beard', 'rows'=>2],
    ['key'=>'category_key', 'label'=>'Category', 'type'=>'select', 'span'=>'col-md-4', 'options'=>$categoryOptions ?? []],
    ['key'=>'price',    'label'=>'Price',     'type'=>'text',  'prefix'=>'₹', 'span'=>'col-md-3', 'placeholder'=>'249'],
    ['key'=>'old',      'label'=>'Old Price', 'type'=>'text',  'prefix'=>'₹', 'span'=>'col-md-3', 'placeholder'=>'349'],
    ['key'=>'tag',      'label'=>'Tag',       'type'=>'text',  'span'=>'col-md-4', 'placeholder'=>'Bestseller'],
    ['key'=>'tagColor', 'label'=>'Tag Color', 'type'=>'color', 'span'=>'col-md-4', 'placeholder'=>'#e67e22'],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'ms-prods',
    'itemLabel'   => 'Product',
    'tableFields' => ['product_image', 'name', 'price'],
    'fields'      => $fields,
])
