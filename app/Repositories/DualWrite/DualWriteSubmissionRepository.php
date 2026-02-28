<?php

namespace App\Repositories\DualWrite;

use App\Models\Vcard;
use App\Repositories\Contracts\SubmissionRepository;
use App\Repositories\Mongo\MongoSubmissionRepository;
use App\Repositories\Sql\SqlSubmissionRepository;
use Illuminate\Database\Eloquent\Model;

class DualWriteSubmissionRepository implements SubmissionRepository
{
    public function __construct(
        private readonly SqlSubmissionRepository $sqlRepository,
        private readonly MongoSubmissionRepository $mongoRepository,
    ) {
    }

    public function create(string $type, Vcard $vcard, array $payload, array $fields = []): Model
    {
        $sqlModel = $this->sqlRepository->create($type, $vcard, $payload, $fields);
        $this->mongoRepository->create($type, $vcard, $payload, $fields);

        return $sqlModel;
    }
}
