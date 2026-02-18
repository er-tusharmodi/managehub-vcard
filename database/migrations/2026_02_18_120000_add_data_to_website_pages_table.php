<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_pages', function (Blueprint $table) {
            $table->json('data')->nullable()->after('footer_links');
        });
    }

    public function down(): void
    {
        Schema::table('website_pages', function (Blueprint $table) {
            $table->dropColumn('data');
        });
    }
};
