<?php

namespace App\Repositories\Contracts;

use App\Models\Vcard;
use Illuminate\Database\Eloquent\Model;

interface SubmissionRepository
{
    public function create(string $type, Vcard $vcard, array $payload, array $fields = []): Model;
}
