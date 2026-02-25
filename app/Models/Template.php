<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_key',
        'display_name',
        'category',
        'description',
        'is_visible',
        'display_order',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Scope to get only visible templates
     */
    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    /**
     * Scope to order templates by display_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc');
    }

    /**
     * Get the preview URL for this template
     */
    public function getPreviewUrlAttribute(): string
    {
        return route('admin.templates.preview', $this->template_key);
    }

    /**
     * Get the template path on filesystem
     */
    public function getTemplatePathAttribute(): string
    {
        return base_path('vcard-template/' . $this->template_key);
    }

    /**
     * Check if template files exist on filesystem
     */
    public function existsOnFilesystem(): bool
    {
        return file_exists($this->template_path) && is_dir($this->template_path);
    }

    /**
     * Get the asset base URL for this template
     */
    public function getAssetBaseUrlAttribute(): string
    {
        return url("template-assets/{$this->template_key}/");
    }
}
