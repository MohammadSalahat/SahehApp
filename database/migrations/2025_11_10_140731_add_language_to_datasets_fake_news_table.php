<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('datasets_fake_news', function (Blueprint $table) {
            $table->string('language', 2)->default('ar')->after('content')->comment('Language code: ar for Arabic, en for English');
            $table->index('language');
        });

        // Update existing records to Arabic
        DB::table('datasets_fake_news')->update(['language' => 'ar']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('datasets_fake_news', function (Blueprint $table) {
            $table->dropIndex(['language']);
            $table->dropColumn('language');
        });
    }
};
