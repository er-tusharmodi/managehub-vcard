<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\VcardTemplateService;
use App\Services\TemplateBackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TemplateController extends Controller
{
    protected $templateService;
    protected $backupService;

    public function __construct(VcardTemplateService $templateService, TemplateBackupService $backupService)
    {
        $this->templateService = $templateService;
        $this->backupService = $backupService;
    }

    /**
     * Display a listing of templates
     */
    public function index()
    {
        $templates = $this->templateService->listTemplates();

        // Add vCard count for each template
        foreach ($templates as &$template) {
            $template['vcard_count'] = $this->templateService->getVcardCount($template['key']);
            $template['can_delete'] = $this->templateService->canDelete($template['key']);
        }

        return view('admin.templates.index', compact('templates'));
    }

    /**
     * Show code editor for template
     */
    public function editCode(string $templateKey)
    {
        try {
            $files = $this->templateService->getTemplateFiles($templateKey);
            $templateName = ucwords(str_replace('-', ' ', $templateKey));

            return view('admin.templates.edit-code', compact('templateKey', 'files', 'templateName'));
        } catch (\Exception $e) {
            return redirect()->route('admin.templates.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Show visual editor for template
     */
    public function editVisual(string $templateKey, ?string $section = null)
    {
        try {
            $data = $this->templateService->getTemplateDefaultJson($templateKey);
            $templateName = ucwords(str_replace('-', ' ', $templateKey));

            return view('admin.templates.edit-visual', compact('templateKey', 'section', 'data', 'templateName'));
        } catch (\Exception $e) {
            return redirect()->route('admin.templates.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Update template code files
     */
    public function updateCode(Request $request, string $templateKey)
    {
        $request->validate([
            'php_content' => 'nullable|string|max:500000',
            'css_content' => 'nullable|string|max:500000',
            'js_content' => 'nullable|string|max:500000',
            'json_content' => 'nullable|string|max:500000',
        ]);

        try {
            // Validate JSON if provided
            if ($request->filled('json_content')) {
                $decoded = json_decode($request->json_content, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return back()->withErrors(['json_content' => 'Invalid JSON syntax: ' . json_last_error_msg()]);
                }
            }

            // Validate PHP syntax if provided
            if ($request->filled('php_content')) {
                $tempFile = tempnam(sys_get_temp_dir(), 'php_');
                file_put_contents($tempFile, $request->php_content);
                
                $output = [];
                $returnVar = 0;
                exec("php -l " . escapeshellarg($tempFile) . " 2>&1", $output, $returnVar);
                
                unlink($tempFile);
                
                if ($returnVar !== 0) {
                    return back()->withErrors(['php_content' => 'PHP syntax error: ' . implode("\n", $output)]);
                }
            }

            // Create backup before updating
            $backupFolder = $this->backupService->backup($templateKey);

            // Update files
            $files = [];
            if ($request->filled('php_content')) {
                $files['php'] = $request->php_content;
            }
            if ($request->filled('css_content')) {
                $files['css'] = $request->css_content;
            }
            if ($request->filled('js_content')) {
                $files['js'] = $request->js_content;
            }
            if ($request->filled('json_content')) {
                $files['json'] = $request->json_content;
            }

            $this->templateService->updateTemplateFiles($templateKey, $files);

            return back()->with('success', 'Template updated successfully! Backup created at: ' . $backupFolder);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update template: ' . $e->getMessage());
        }
    }

    /**
     * Update template visual data (default.json)
     */
    public function updateVisual(Request $request, string $templateKey)
    {
        $request->validate([
            'data' => 'required|array',
        ]);

        try {
            // Create backup before updating
            $this->backupService->backup($templateKey);

            // Update default.json
            $this->templateService->updateTemplateDefaultJson($templateKey, $request->data);

            return response()->json([
                'success' => true,
                'message' => 'Template data updated successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update template: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a template
     */
    public function destroy(string $templateKey)
    {
        try {
            if (!$this->templateService->canDelete($templateKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete template. vCards are currently using this template.',
                ], 422);
            }

            // Create final backup before deletion
            $this->backupService->backup($templateKey);

            // Delete template
            $this->templateService->deleteTemplate($templateKey);

            return response()->json([
                'success' => true,
                'message' => 'Template deleted successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete template: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Preview template with sample data
     */
    public function preview(string $templateKey)
    {
        try {
            $templatePath = $this->templateService->templatePath($templateKey);
            
            if (!$templatePath) {
                abort(404, 'Template not found');
            }

            $indexPath = $templatePath . DIRECTORY_SEPARATOR . 'index.php';
            
            if (!File::exists($indexPath)) {
                abort(404, 'Template index.php not found');
            }

            // Execute template in separate PHP process to avoid function conflicts
            $phpBinary = PHP_BINARY;
            $command = escapeshellarg($phpBinary) . ' ' . escapeshellarg($indexPath);
            $content = shell_exec($command);

            if (!is_string($content) || empty($content)) {
                abort(500, 'Failed to render template');
            }

            // Inject base tag for relative asset paths
            $baseHref = rtrim(url("template-assets/{$templateKey}"), '/') . '/';
            $baseTag = '<base href="' . $baseHref . '">';
            $content = preg_replace('/(<head[^>]*>)/i', '$1' . $baseTag, $content, 1, $count);

            if ($count === 0) {
                $content = $baseTag . $content;
            }

            return response($content)->header('Content-Type', 'text/html');
        } catch (\Exception $e) {
            abort(500, 'Failed to preview template: ' . $e->getMessage());
        }
    }

    /**
     * Serve template assets for preview
     */
    public function asset(string $templateKey, string $path)
    {
        $templatePath = $this->templateService->templatePath($templateKey);

        if (!$templatePath) {
            abort(404, 'Template not found');
        }

        $safePath = ltrim($path, '/');
        $fullPath = $templatePath . DIRECTORY_SEPARATOR . $safePath;
        $realTemplatePath = realpath($templatePath);
        $realAssetPath = realpath($fullPath);

        if (!$realTemplatePath || !$realAssetPath || strncmp($realAssetPath, $realTemplatePath, strlen($realTemplatePath)) !== 0) {
            abort(404);
        }

        if (!File::exists($realAssetPath) || !File::isFile($realAssetPath)) {
            abort(404);
        }

        $extension = strtolower(pathinfo($realAssetPath, PATHINFO_EXTENSION));
        $mimeMap = [
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'svg' => 'image/svg+xml',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'woff' => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf' => 'font/ttf',
            'eot' => 'application/vnd.ms-fontobject',
        ];
        $mimeType = $mimeMap[$extension] ?? File::mimeType($realAssetPath) ?? 'application/octet-stream';

        return response(File::get($realAssetPath))
            ->header('Content-Type', $mimeType);
    }
}
