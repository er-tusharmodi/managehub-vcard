<?php

namespace App\Console\Commands;

use App\Models\Mongo\VcardContent;
use App\Models\Vcard;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateVcardJsonToDb extends Command
{
    protected $signature = 'vcards:migrate-json-to-db
                            {--dry-run : Show what would be migrated without making any changes}
                            {--delete-json : Delete JSON files from disk after migration}';

    protected $description = 'Migrate vCard template data from JSON files / VcardContent documents into Vcard.data_content';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $deleteJson = $this->option('delete-json');

        if ($dryRun) {
            $this->warn('DRY RUN — no changes will be written.');
        }

        $this->info('Scanning all vCards...');
        $this->newLine();

        $vcards = Vcard::all();

        if ($vcards->isEmpty()) {
            $this->warn('No vCards found.');
            return self::SUCCESS;
        }

        $alreadyHad = 0;
        $migratedFromVcardContent = 0;
        $migratedFromJson = 0;
        $skipped = 0;
        $errors = 0;

        $bar = $this->output->createProgressBar($vcards->count());
        $bar->start();

        foreach ($vcards as $vcard) {
            try {
                // 1. Already has data_content on the Vcard document — nothing to do
                $fresh = $vcard->fresh();
                if ($fresh && is_array($fresh->data_content) && !empty($fresh->data_content)) {
                    $alreadyHad++;

                    // Still delete JSON files if requested
                    if ($deleteJson && !$dryRun) {
                        $this->deleteJsonFiles($vcard);
                    }

                    $bar->advance();
                    continue;
                }

                $data = null;

                // 2. Try legacy VcardContent document
                $document = VcardContent::query()
                    ->where('legacy_vcard_id', $vcard->id)
                    ->first();

                if ($document && is_array($document->data_content) && !empty($document->data_content)) {
                    $data = $document->data_content;
                    $source = 'VcardContent';
                    $migratedFromVcardContent++;
                }

                // 3. Fall back to JSON file on disk
                if ($data === null && $vcard->data_path && Storage::disk('public')->exists($vcard->data_path)) {
                    $raw = Storage::disk('public')->get($vcard->data_path);
                    $parsed = json_decode($raw, true);
                    if (is_array($parsed) && !empty($parsed)) {
                        $data = $parsed;
                        $source = 'JSON file';
                        $migratedFromJson++;
                    }
                }

                if ($data === null) {
                    $this->newLine();
                    $this->warn("  ⊘ Skipped (no data found): {$vcard->subdomain}");
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                if (!$dryRun) {
                    $vcard->update(['data_content' => $data]);

                    if ($deleteJson) {
                        $this->deleteJsonFiles($vcard);
                    }
                } else {
                    $this->newLine();
                    $this->line("  [dry-run] Would migrate {$vcard->subdomain} from {$source}");
                }

            } catch (\Exception $e) {
                $this->newLine();
                $this->error("  ✗ Error ({$vcard->subdomain}): {$e->getMessage()}");
                $errors++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info('Migration summary:');
        $this->table(
            ['Status', 'Count'],
            [
                ['Already had data_content', $alreadyHad],
                ['Migrated from VcardContent', $migratedFromVcardContent],
                ['Migrated from JSON file', $migratedFromJson],
                ['Skipped (no data)', $skipped],
                ['Errors', $errors],
            ]
        );

        if ($dryRun) {
            $this->warn('DRY RUN complete — no changes written. Re-run without --dry-run to apply.');
        }

        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function deleteJsonFiles(Vcard $vcard): void
    {
        $paths = [
            'vcards/' . $vcard->subdomain . '/data.json',
            'vcards/' . $vcard->subdomain . '/template/default.json',
        ];

        foreach ($paths as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }
}
