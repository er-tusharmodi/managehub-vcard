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
                $table->string('page_url')->nullable()->after('user_agent');
                $table->index('page_url');
            }
            if (!Schema::hasColumn('vcard_visits', 'referrer')) {
                $table->string('referrer')->nullable()->after('page_url');
                $table->index('referrer');
            }
            if (!Schema::hasColumn('vcard_visits', 'browser')) {
                $table->string('browser')->nullable()->after('referrer');
                $table->index('browser');
            }
            if (!Schema::hasColumn('vcard_visits', 'device')) {
                $table->string('device')->nullable()->after('browser');
                $table->index('device');
            }
            if (!Schema::hasColumn('vcard_visits', 'platform')) {
                $table->string('platform')->nullable()->after('device');
            }
            if (!Schema::hasColumn('vcard_visits', 'country')) {
                $table->string('country')->nullable()->after('platform');
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
