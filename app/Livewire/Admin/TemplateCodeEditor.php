<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Services\VcardTemplateService;
use App\Services\TemplateBackupService;

class TemplateCodeEditor extends Component
{
    public $templateKey;
    public $templateName;
    public $activeTab = 'php';
    public $phpContent = '';
    public $cssContent = '';
    public $jsContent = '';
    public $jsonContent = '';
    public $previewKey;

    protected $templateService;
    protected $backupService;

    public function boot(VcardTemplateService $templateService, TemplateBackupService $backupService)
    {
        $this->templateService = $templateService;
        $this->backupService = $backupService;
    }

    public function mount($templateKey)
    {
        $this->templateKey = $templateKey;
        $this->templateName = ucwords(str_replace('-', ' ', $templateKey));
        $this->previewKey = time();
        
        try {
            $files = $this->templateService->getTemplateFiles($templateKey);
            $this->phpContent = $files['php'] ?? '';
            $this->cssContent = $files['css'] ?? '';
            $this->jsContent = $files['js'] ?? '';
            $this->jsonContent = $files['json'] ?? '';
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->route('admin.templates.index');
        }
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function save()
    {
        try {
            // Validate JSON if it's not empty
            if (!empty($this->jsonContent)) {
                $decoded = json_decode($this->jsonContent, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $this->dispatch('notify', type: 'error', message: 'Invalid JSON syntax: ' . json_last_error_msg());
                    return;
                }
            }

            // Validate PHP syntax if not empty
            if (!empty($this->phpContent)) {
                $tempFile = tempnam(sys_get_temp_dir(), 'php_');
                file_put_contents($tempFile, $this->phpContent);
                
                $output = [];
                $returnVar = 0;
                exec("php -l " . escapeshellarg($tempFile) . " 2>&1", $output, $returnVar);
                
                unlink($tempFile);
                
                if ($returnVar !== 0) {
                    $this->dispatch('notify', type: 'error', message: 'PHP syntax error: ' . implode("\n", $output));
                    return;
                }
            }

            // Create backup
            $this->backupService->backup($this->templateKey);

            // Update files
            $files = [
                'php' => $this->phpContent,
                'css' => $this->cssContent,
                'js' => $this->jsContent,
                'json' => $this->jsonContent,
            ];

            $this->templateService->updateTemplateFiles($this->templateKey, $files);

            // Refresh preview
            $this->previewKey = time();
            
            $this->dispatch('notify', type: 'success', message: 'Template updated successfully!');
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Failed to update: ' . $e->getMessage());
        }
    }

    public function refreshPreview()
    {
        $this->previewKey = time();
    }

    public function render()
    {
        return view('livewire.admin.template-code-editor')
            ->layout('layouts.admin-livewire');
    }
}
