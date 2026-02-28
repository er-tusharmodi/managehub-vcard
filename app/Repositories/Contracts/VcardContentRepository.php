<?php

namespace App\Repositories\Contracts;

use App\Models\Vcard;

interface VcardContentRepository
{
    public function load(Vcard $vcard): array;

    public function save(Vcard $vcard, array $payload): void;
}
