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
        Schema::table('datasets_fake_news', function (Blueprint $table) {
            // Add hash index for exact content matching (performance optimization)
            $table->index('content_hash', 'idx_content_hash');

            // Add composite index for language + confidence filtering
            $table->index(['language', 'confidence_score'], 'idx_language_confidence');

            // Add index for title + content FULLTEXT search (if not already exists)
            $table->fullText(['title', 'content'], 'idx_title_content_fulltext');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('datasets_fake_news', function (Blueprint $table) {
            $table->dropIndex('idx_content_hash');
            $table->dropIndex('idx_language_confidence');
            $table->dropIndex('idx_title_content_fulltext');
        });
    }
};
