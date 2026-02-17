<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('website_pages', function (Blueprint $table) {
            $table->string('hero_title_highlight')->nullable()->after('hero_title');
            $table->json('header_cta')->nullable()->after('hero_subtitle');
            $table->json('hero_buttons')->nullable()->after('header_cta');
            $table->json('categories')->nullable()->after('hero_image_path');
            $table->json('vcard_preview')->nullable()->after('categories');
            $table->json('how_it_works')->nullable()->after('vcard_preview');
            $table->json('cta_section')->nullable()->after('how_it_works');
            $table->text('footer_about')->nullable()->after('footer_text');
            $table->json('footer_links')->nullable()->after('footer_about');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_pages', function (Blueprint $table) {
            $table->dropColumn([
                'hero_title_highlight',
                'header_cta',
                'hero_buttons',
                'categories',
                'vcard_preview',
                'how_it_works',
                'cta_section',
                'footer_about',
                'footer_links',
            ]);
        });
    }
};
