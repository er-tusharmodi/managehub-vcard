<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Vcard;
use App\Repositories\Contracts\VcardContentRepository;

class SyncSectionsConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vcards:sync-sections';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync and merge _sections_config and _field_config from templates to existing vCard data in the database';

    public function __construct(private readonly VcardContentRepository $contentRepository)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting vCard sections config sync...');
        $this->newLine();

        $vcards = Vcard::all();
        
        if ($vcards->isEmpty()) {
            $this->warn('No vCards found in database.');
            return Command::SUCCESS;
        }

        $updated = 0;
        $skipped = 0;
        $errors = 0;

        $bar = $this->output->createProgressBar($vcards->count());
        $bar->start();

        foreach ($vcards as $vcard) {
            try {
                // Load vCard's data from the repository (DB)
                $data = $this->contentRepository->load($vcard);

                if (empty($data)) {
                    $this->newLine();
                    $this->warn("⚠ Skipped: {$vcard->subdomain} - no data found");
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                // Load template's default.json to merge _sections_config and _field_config
                $templatePath = base_path('vcard-template/' . $vcard->template_key . '/default.json');
                
                if (!file_exists($templatePath)) {
                    $this->newLine();
                    $this->warn("⚠ Skipped: {$vcard->subdomain} - Template not found: {$vcard->template_key}");
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                $templateContent = file_get_contents($templatePath);
                $templateData = json_decode($templateContent, true);

                if (json_last_error() !== JSON_ERROR_NONE || !isset($templateData['_sections_config'])) {
                    $this->newLine();
                    $this->warn("⚠ Skipped: {$vcard->subdomain} - Template has no _sections_config");
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                // Merge _sections_config: add any new keys from template without overwriting existing per-vCard values
                $existingConfig = $data['_sections_config'] ?? [];
                $mergedConfig = array_merge($templateData['_sections_config'], $existingConfig);
                $data['_sections_config'] = $mergedConfig;

                // Merge _field_config: add any new section field configs from template
                if (isset($templateData['_field_config'])) {
                    $existingFieldConfig = $data['_field_config'] ?? [];
                    $data['_field_config'] = array_merge($templateData['_field_config'], $existingFieldConfig);
                }

                // Ensure meta object has SEO fields (description, keywords, og_image)
                if (isset($data['meta'])) {
                    $data['meta'] = array_merge(
                        ['description' => '', 'keywords' => '', 'og_image' => ''],
                        $data['meta']
                    );
                }

                // Save updated data to the repository (DB)
                $this->contentRepository->save($vcard, $data);

                $updated++;
                
            } catch (\Exception $e) {
                $this->newLine();
                $this->error("✗ Error: {$vcard->subdomain} - {$e->getMessage()}");
                $errors++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Summary
        $this->info('╔═══════════════════════════════════════╗');
        $this->info('║     Sync Completed Successfully!     ║');
        $this->info('╚═══════════════════════════════════════╝');
        $this->newLine();
        $this->line("Total vCards: {$vcards->count()}");
        $this->line("<fg=green>✓ Updated: {$updated}</>");
        $this->line("<fg=yellow>⊘ Skipped: {$skipped}</>");
        
        if ($errors > 0) {
            $this->line("<fg=red>✗ Errors: {$errors}</>");
        }

        $this->newLine();

        return Command::SUCCESS;
    }
}

