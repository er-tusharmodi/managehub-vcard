<?php

namespace App\Repositories\Mongo;

use App\Models\Mongo\VcardContent;
use App\Models\Vcard;
use App\Repositories\Contracts\VcardContentRepository;
use App\Repositories\Sql\SqlVcardContentRepository;

class MongoVcardContentRepository implements VcardContentRepository
{
    public function __construct(private readonly SqlVcardContentRepository $fallbackRepository)
    {
    }

    public function load(Vcard $vcard): array
    {
        $document = VcardContent::query()
            ->where('legacy_vcard_id', $vcard->id)
            ->first();

        if ($document && is_array($document->data_content)) {
            return $document->data_content;
        }

        return $this->fallbackRepository->load($vcard);
    }

    public function save(Vcard $vcard, array $payload): void
    {
        VcardContent::query()->updateOrCreate(
            ['legacy_vcard_id' => $vcard->id],
            [
                'user_id' => $vcard->user_id,
                'subdomain' => $vcard->subdomain,
                'template_key' => $vcard->template_key,
                'client_name' => $vcard->client_name,
                'client_email' => $vcard->client_email,
                'client_phone' => $vcard->client_phone,
                'client_address' => $vcard->client_address,
                'qr_code_path' => $vcard->qr_code_path,
                'status' => $vcard->status,
                'subscription_status' => $vcard->subscription_status,
                'subscription_started_at' => $vcard->subscription_started_at,
                'subscription_expires_at' => $vcard->subscription_expires_at,
                'domain_verified_at' => $vcard->domain_verified_at,
                'created_by' => $vcard->created_by,
                'data_content' => $payload,
            ]
        );
    }
}
