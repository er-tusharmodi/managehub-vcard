{-- minimart-template/products.blade.php --}
@php
$items = $form ?? [];
$fields = [
    ['key'=>'product_image', 'label'=>'Photo', 'type'=>'image', 'span'=>'col-md-3'],
    ['key'=>'name', 'label'=>'Product Name', 'type'=>'text', 'span'=>'col-md-6', 'placeholder'=>'Amul Full Cream Milk'],
    ['key'=>'desc', 'label'=>'Description', 'type'=>'textarea', 'span'=>'col-12', 'placeholder'=>'500ml pouch, pasteurised', 'rows'=>2],
    ['key'=>'category_key', 'label'=>'Category', 'type'=>'select', 'span'=>'col-md-4', 'options'=>$categoryOptions ?? []],
    ['key'=>'price',    'label'=>'Price (₹)',  'type'=>'text',  'prefix'=>'₹', 'span'=>'col-md-3', 'placeholder'=>'28'],
    ['key'=>'oldPrice', 'label'=>'Old Price',  'type'=>'text',  'prefix'=>'₹', 'span'=>'col-md-3', 'placeholder'=>'32'],
    ['key'=>'per',      'label'=>'Per Unit',   'type'=>'text',  'span'=>'col-md-3', 'placeholder'=>'500ml'],
    ['key'=>'tag',      'label'=>'Tag',        'type'=>'text',  'span'=>'col-md-4', 'placeholder'=>'Fresh'],
    ['key'=>'tagColor', 'label'=>'Tag Color',  'type'=>'color', 'span'=>'col-md-4', 'placeholder'=>'#27ae60'],
];
@endphp
@include('livewire.vcards.forms._shared._list_table', [
    'items'       => $items,
    'addPath'     => '',
    'modelBase'   => 'form',
    'sectionKey'  => 'mm-prods',
    'itemLabel'   => 'Product',
    'tableFields' => ['product_image', 'name', 'price'],
    'fields'      => $fields,
])
