<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsitePage extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'meta_title',
        'meta_description',
        'hero_title',
        'hero_title_highlight',
        'hero_subtitle',
        'header_cta',
        'hero_buttons',
        'hero_image_path',
        'categories',
        'vcard_preview',
        'how_it_works',
        'cta_section',
        'about_title',
        'about_body',
        'about_image_path',
        'services',
        'testimonials',
        'faqs',
        'footer_text',
        'footer_about',
        'footer_links',
    ];

    protected $casts = [
        'header_cta' => 'array',
        'hero_buttons' => 'array',
        'categories' => 'array',
        'vcard_preview' => 'array',
        'how_it_works' => 'array',
        'cta_section' => 'array',
        'services' => 'array',
        'testimonials' => 'array',
        'faqs' => 'array',
        'footer_links' => 'array',
    ];
}
