<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vcards', function (Blueprint $table) {
            $table->dropColumn(['godaddy_record_id', 'verification_token', 'domain_verified_at']);
        });
    }

    public function down(): void
    {
        Schema::table('vcards', function (Blueprint $table) {
            $table->string('godaddy_record_id')->nullable();
            $table->string('verification_token')->nullable();
            $table->dateTime('domain_verified_at')->nullable();
        });
    }
};
