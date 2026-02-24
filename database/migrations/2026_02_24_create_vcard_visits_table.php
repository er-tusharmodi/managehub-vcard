<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vcard_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vcard_id')->constrained()->cascadeOnDelete();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('visited_at')->useCurrent();
            $table->timestamps();
            
            $table->index(['vcard_id', 'visited_at']);
            $table->index('ip_address');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vcard_visits');
    }
};
