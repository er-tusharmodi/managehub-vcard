<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vcards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('subdomain')->unique();
            $table->string('template_key');
            $table->string('client_name');
            $table->string('client_email');
            $table->string('client_phone')->nullable();
            $table->string('client_address')->nullable();
            $table->string('data_path')->nullable();
            $table->string('template_path')->nullable();
            $table->string('status')->default('draft');
            $table->string('godaddy_record_id')->nullable();
            $table->string('verification_token')->nullable();
            $table->timestamp('domain_verified_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vcards');
    }
};
