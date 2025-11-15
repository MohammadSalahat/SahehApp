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
        Schema::table('chatgpt_verifications', function (Blueprint $table) {
            // Source verification tracking fields
            $table->json('sources_checked')->nullable()->comment('Array of source URLs that were checked')->after('fact_check_sources');
            $table->json('source_verification_status')->nullable()->comment('Status of source verification results')->after('sources_checked');
            $table->json('trusted_sources_used')->nullable()->comment('List of trusted sources passed to ChatGPT')->after('source_verification_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chatgpt_verifications', function (Blueprint $table) {
            $table->dropColumn(['sources_checked', 'source_verification_status', 'trusted_sources_used']);
        });
    }
};
