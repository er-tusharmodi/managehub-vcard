<?php

namespace App\Repositories\Mongo;

use App\Models\Mongo\VcardContent;
use App\Models\Vcard;
use App\Repositories\Contracts\VcardContentRepository;
use App\Repositories\Sql\SqlVcardContentRepository;
use Illuminate\Support\Facades\Log;

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
        try {
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
        } catch (\MongoDB\Driver\Exception\BulkWriteException $e) {
            // Handle E11000 duplicate key error on subdomain
            if (str_contains($e->getMessage(), 'E11000')) {
                Log::warning('MongoDB E11000 duplicate key error on subdomain', [
                    'vcard_id' => $vcard->id,
                    'subdomain' => $vcard->subdomain,
                    'error' => $e->getMessage(),
                ]);

                // Try to find and update the document by subdomain instead
                $document = VcardContent::query()
                    ->where('subdomain', $vcard->subdomain)
                    ->first();
                
                if ($document) {
                    Log::info('Resolved E11000 error by updating existing document', [
                        'old_legacy_vcard_id' => $document->legacy_vcard_id,
                        'new_legacy_vcard_id' => $vcard->id,
                        'subdomain' => $vcard->subdomain,
                    ]);

                    // Update the existing document with the new legacy_vcard_id and data
                    $document->update([
                        'legacy_vcard_id' => $vcard->id,
                        'user_id' => $vcard->user_id,
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
                    ]);
                } else {
                    // If no document with that subdomain exists, re-throw the exception
                    Log::error('E11000 error but no document found with subdomain', [
                        'vcard_id' => $vcard->id,
                        'subdomain' => $vcard->subdomain,
                    ]);
                    throw $e;
                }
            } else {
                throw $e;
            }
        }
    }
}
