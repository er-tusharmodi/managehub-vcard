<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vcard_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vcard_id')->constrained()->cascadeOnDelete();
            $table->string('source_template')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('message')->nullable();
            $table->json('items')->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index(['vcard_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vcard_bookings');
    }
};
