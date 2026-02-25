<?php

namespace App\Console\Commands;

use App\Models\Template;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SyncTemplates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'templates:sync {--clean : Remove database records for deleted templates}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync templates from filesystem to database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 Syncing templates from filesystem...');
        $this->newLine();

        $templateRoot = base_path('vcard-template');
        
        if (!File::exists($templateRoot)) {
            $this->error("❌ Template directory not found: {$templateRoot}");
            return Command::FAILURE;
        }

        $directories = File::directories($templateRoot);
        $syncedCount = 0;
        $skippedCount = 0;
        $maxDisplayOrder = Template::max('display_order') ?? 0;

        // Sync filesystem templates to database
        foreach ($directories as $dir) {
            $templateKey = basename($dir);
            
            // Skip if not a directory or doesn't have index.php
            if (!File::exists($dir . '/index.php')) {
                $this->warn("⚠️  Skipping {$templateKey}: No index.php found");
                $skippedCount++;
                continue;
            }

            // Generate display name from template key
            $displayName = $this->generateDisplayName($templateKey);
            
            // Try to extract category from folder name
            $category = $this->extractCategory($templateKey);

            // Check if template already exists
            $template = Template::where('template_key', $templateKey)->first();

            if ($template) {
                $this->line("   Already exists: {$displayName} ({$templateKey})");
            } else {
                // Create new template record
                Template::create([
                    'template_key' => $templateKey,
                    'display_name' => $displayName,
                    'category' => $category,
                    'is_visible' => true,
                    'display_order' => ++$maxDisplayOrder,
                ]);
                
                $this->info("✅ Added: {$displayName} ({$templateKey})");
                $syncedCount++;
            }
        }

        // Clean up orphaned database records if requested
        if ($this->option('clean')) {
            $this->newLine();
            $this->info('🧹 Cleaning up orphaned database records...');
            
            $filesystemTemplates = array_map(fn($dir) => basename($dir), $directories);
            $orphanedTemplates = Template::whereNotIn('template_key', $filesystemTemplates)->get();

            if ($orphanedTemplates->isEmpty()) {
                $this->line('   No orphaned records found');
            } else {
                foreach ($orphanedTemplates as $orphan) {
                    if ($this->confirm("Delete database record for '{$orphan->display_name}' (template files not found)?", true)) {
                        $orphan->delete();
                        $this->warn("🗑️  Removed: {$orphan->display_name}");
                    }
                }
            }
        }

        // Summary
        $this->newLine();
        $this->info('📊 Sync Summary:');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total templates in filesystem', count($directories)],
                ['New templates added', $syncedCount],
                ['Existing templates', count($directories) - $syncedCount - $skippedCount],
                ['Skipped (no index.php)', $skippedCount],
                ['Total in database', Template::count()],
                ['Visible on home page', Template::visible()->count()],
            ]
        );

        return Command::SUCCESS;
    }

    /**
     * Generate a display name from template key
     */
    private function generateDisplayName(string $templateKey): string
    {
        // Remove '-template' suffix if exists
        $name = str_replace('-template', '', $templateKey);
        
        // Convert kebab-case to Title Case
        return Str::title(str_replace('-', ' ', $name));
    }

    /**
     * Extract category from template key
     */
    private function extractCategory(string $templateKey): ?string
    {
        $categoryMap = [
            'doctor' => 'Healthcare',
            'clinic' => 'Healthcare',
            'salon' => 'Beauty',
            'barber' => 'Beauty',
            'restaurant' => 'Food & Dining',
            'cafe' => 'Food & Dining',
            'shop' => 'Retail',
            'minimart' => 'Retail',
            'electronics' => 'Electronics',
            'jewelry' => 'Jewelry',
            'sweetshop' => 'Food & Dining',
            'mens-salon' => 'Beauty',
        ];

        foreach ($categoryMap as $keyword => $category) {
            if (Str::contains($templateKey, $keyword)) {
                return $category;
            }
        }

        return 'General';
    }
}
