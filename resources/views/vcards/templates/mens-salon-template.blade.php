@php
    $__templateDir = base_path('vcard-template/mens-salon-template');
    ob_start();
    include $__templateDir . '/index.php';
    $__html = ob_get_clean();
    $__html = preg_replace(
        '/<head>/i',
        '<head><base href="' . e($assetBase) . '"><meta name="csrf-token" content="' . csrf_token() . '">',
        $__html, 1
    );
    $__html = str_replace(
        'window.__VCARD_SUBDOMAIN__ = "";',
        'window.__VCARD_SUBDOMAIN__ = ' . json_encode($subdomain) . ';',
        $__html
    );
    echo $__html;
@endphp
