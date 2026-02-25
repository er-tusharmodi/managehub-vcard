<?php

namespace App\Console\Commands;

use App\Models\Vcard;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UpdateVcardTemplates extends Command
{
    protected $signature = 'vcards:update-templates';
    protected $description = 'Update all vCard template files from source templates';

    public function handle()
    {
        $this->info('Updating vCard template files...');
        
        $vcards = Vcard::whereNotNull('template_path')->get();
        $updated = 0;
        $skipped = 0;
        $errors = 0;

        $progressBar = $this->output->createProgressBar($vcards->count());
        $progressBar->start();

        foreach ($vcards as $vcard) {
            try {
                // Get template key from vCard model
                $templateKey = $vcard->template_key;
                
                if (!$templateKey) {
                    $this->newLine();
                    $this->warn("Template key not found for vCard: {$vcard->subdomain}");
                    $skipped++;
                    $progressBar->advance();
                    continue;
                }

                // Get template path
                $templatePath = Storage::disk('public')->path($vcard->template_path);
                
                if (!is_dir($templatePath)) {
                    $this->newLine();
                    $this->warn("Template directory not found for vCard: {$vcard->subdomain}");
                    $skipped++;
                    $progressBar->advance();
                    continue;
                }

                // Source template directory
                $sourceTemplateDir = base_path("vcard-template/{$templateKey}");
                
                if (!is_dir($sourceTemplateDir)) {
                    $this->newLine();
                    $this->warn("Source template not found: {$templateKey} for vCard: {$vcard->subdomain}");
                    $skipped++;
                    $progressBar->advance();
                    continue;
                }

                // Copy template files from source to vCard template
                $filesToCopy = ['index.php', 'script.js', 'style.css', 'default.json'];
                $copiedFiles = 0;

                foreach ($filesToCopy as $file) {
                    $sourcePath = $sourceTemplateDir . '/' . $file;
                    $destPath = $templatePath . '/' . $file;

                    if (file_exists($sourcePath)) {
                        File::copy($sourcePath, $destPath);
                        $copiedFiles++;
                    }
                }

                if ($copiedFiles > 0) {
                    $updated++;
                } else {
                    $this->newLine();
                    $this->warn("No template files found for template: {$templateKey}");
                    $skipped++;
                }

            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Error updating vCard {$vcard->subdomain}: {$e->getMessage()}");
                $errors++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info("Update completed:");
        $this->table(
            ['Status', 'Count'],
            [
                ['Updated', $updated],
                ['Skipped', $skipped],
                ['Errors', $errors],
            ]
        );

        return Command::SUCCESS;
    }
}
