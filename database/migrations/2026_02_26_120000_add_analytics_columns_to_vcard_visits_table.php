<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vcard_visits', function (Blueprint $table) {
            if (!Schema::hasColumn('vcard_visits', 'page_url')) {
                $table->string('page_url')->nullable();
                $table->index('page_url');
            }
            if (!Schema::hasColumn('vcard_visits', 'referrer')) {
                $table->string('referrer')->nullable();
                $table->index('referrer');
            }
            if (!Schema::hasColumn('vcard_visits', 'browser')) {
                $table->string('browser')->nullable();
                $table->index('browser');
            }
            if (!Schema::hasColumn('vcard_visits', 'device')) {
                $table->string('device')->nullable();
                $table->index('device');
            }
            if (!Schema::hasColumn('vcard_visits', 'platform')) {
                $table->string('platform')->nullable();
            }
            if (!Schema::hasColumn('vcard_visits', 'country')) {
                $table->string('country')->nullable();
                $table->index('country');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vcard_visits', function (Blueprint $table) {
            if (Schema::hasColumn('vcard_visits', 'page_url')) {
                $table->dropIndex(['page_url']);
            }
            if (Schema::hasColumn('vcard_visits', 'referrer')) {
                $table->dropIndex(['referrer']);
            }
            if (Schema::hasColumn('vcard_visits', 'device')) {
                $table->dropIndex(['device']);
            }
            if (Schema::hasColumn('vcard_visits', 'browser')) {
                $table->dropIndex(['browser']);
            }
            if (Schema::hasColumn('vcard_visits', 'country')) {
                $table->dropIndex(['country']);
            }

            $columns = [];
            foreach (['page_url', 'referrer', 'browser', 'device', 'platform', 'country'] as $column) {
                if (Schema::hasColumn('vcard_visits', $column)) {
                    $columns[] = $column;
                }
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
