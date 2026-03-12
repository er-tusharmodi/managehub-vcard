<?php

namespace App\Repositories\Mongo;

use App\Models\Mongo\VcardContent;
use App\Models\Vcard;
use App\Repositories\Contracts\VcardContentRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class MongoVcardContentRepository implements VcardContentRepository
{
    public function load(Vcard $vcard): array
    {
        // Primary: data stored directly in the Vcard document
        $fresh = $vcard->fresh();
        if ($fresh && is_array($fresh->data_content) && !empty($fresh->data_content)) {
            return $fresh->data_content;
        }

        // Legacy fallback: data stored in a separate VcardContent document (pre-migration)
        $document = VcardContent::query()
            ->where('legacy_vcard_id', $vcard->id)
            ->first();

        if ($document && is_array($document->data_content) && !empty($document->data_content)) {
            Log::info('MongoVcardContentRepository: loaded from legacy VcardContent doc, consider running vcards:migrate-json-to-db', [
                'vcard_id' => $vcard->id,
                'subdomain' => $vcard->subdomain,
            ]);
            return $document->data_content;
        }

        // Last resort: return template default.json seed (for brand-new vcards with no saved data yet)
        $templateRoot = config('vcard.template_root');
        if ($templateRoot && $vcard->template_key) {
            $filePath = $templateRoot . DIRECTORY_SEPARATOR . $vcard->template_key . DIRECTORY_SEPARATOR . 'default.json';
            if (File::exists($filePath)) {
                $data = json_decode(File::get($filePath), true);
                if (is_array($data)) {
                    return $data;
                }
            }
        }

        return [];
    }

    public function save(Vcard $vcard, array $payload): void
    {
        $vcard->update(['data_content' => $payload]);
    }
}
