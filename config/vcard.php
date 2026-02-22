<?php

return [
    'base_domain' => env('VCARD_BASE_DOMAIN', 'managehub.in'),
    'cname_target' => env('VCARD_CNAME_TARGET', 'managehub.in'),
    'record_type' => env('VCARD_RECORD_TYPE', 'A'),
    'template_root' => base_path('vcard-template'),
    'storage_root' => 'vcards',
];
