<?php

namespace App\Repositories\DualWrite;

use App\Models\Vcard;
use App\Repositories\Contracts\VcardContentRepository;
use App\Repositories\Mongo\MongoVcardContentRepository;
use App\Repositories\Sql\SqlVcardContentRepository;

class DualWriteVcardContentRepository implements VcardContentRepository
{
    public function __construct(
        private readonly SqlVcardContentRepository $sqlRepository,
        private readonly MongoVcardContentRepository $mongoRepository,
    ) {
    }

    public function load(Vcard $vcard): array
    {
        return $this->sqlRepository->load($vcard);
    }

    public function save(Vcard $vcard, array $payload): void
    {
        $this->sqlRepository->save($vcard, $payload);
        $this->mongoRepository->save($vcard, $payload);
    }
}
