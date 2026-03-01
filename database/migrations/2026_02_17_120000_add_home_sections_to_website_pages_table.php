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
            $table->string('hero_title_highlight')->nullable();
            $table->json('header_cta')->nullable();
            $table->json('hero_buttons')->nullable();
            $table->json('categories')->nullable();
            $table->json('vcard_preview')->nullable();
            $table->json('how_it_works')->nullable();
            $table->json('cta_section')->nullable();
            $table->text('footer_about')->nullable();
            $table->json('footer_links')->nullable();
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
