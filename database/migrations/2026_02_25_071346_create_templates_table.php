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
        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->string('template_key')->unique()->comment('Folder name in vcard-template/');
            $table->string('display_name')->comment('Admin-editable display name for frontend');
            $table->string('category')->nullable()->comment('Category badge text (e.g., Doctor, Salon)');
            $table->text('description')->nullable()->comment('Optional template description');
            $table->boolean('is_visible')->default(true)->comment('Show on home page or not');
            $table->integer('display_order')->default(0)->comment('Sorting order for frontend display');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
