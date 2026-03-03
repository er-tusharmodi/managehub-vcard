<?php

namespace Database\Seeders;

use App\Models\Template;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templateRoot = base_path('vcard-template');
        
        if (!File::exists($templateRoot)) {
            $this->command->error("Template directory not found: {$templateRoot}");
            return;
        }

        $directories = File::directories($templateRoot);
        $order = 0;

        foreach ($directories as $dir) {
            $templateKey = basename($dir);
            
            // Skip if no corresponding Blade view exists
            $bladeView = resource_path('views/vcards/templates/' . $templateKey . '.blade.php');
            if (!File::exists($bladeView)) {
                $this->command->warn("Skipping {$templateKey}: No Blade view found");
                continue;
            }

            // Generate display name from template key
            $displayName = $this->generateDisplayName($templateKey);
            
            // Try to extract category from folder name
            $category = $this->extractCategory($templateKey);

            // Create or update template
            $template = Template::updateOrCreate(
                ['template_key' => $templateKey],
                [
                    'display_name' => $displayName,
                    'category' => $category,
                    'is_visible' => true,
                    'display_order' => $order++,
                ]
            );

            $this->command->info("✓ Synced template: {$displayName} ({$templateKey})");
        }

        $this->command->info("\nTemplate sync completed! Total: " . Template::count());
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
