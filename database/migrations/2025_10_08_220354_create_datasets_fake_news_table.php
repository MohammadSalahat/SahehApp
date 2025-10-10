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
        Schema::create('datasets_fake_news', function (Blueprint $table) {
            $table->id();
            $table->string('title', 500);
            $table->longText('content');
            $table->timestamp('detected_at')->useCurrent();
            $table->decimal('confidence_score', 5, 4)->default(0.0000)->comment('Score from 0.0000 to 1.0000');
            $table->string('origin_dataset_name', 100)->nullable()->comment('e.g., LIAR, CredBank, or Custom');
            $table->boolean('added_by_ai')->default(false)->comment('True if added by AI system, false if from predefined dataset');
            $table->string('content_hash', 64)->unique()->comment('SHA-256 hash for duplicate detection');
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('detected_at');
            $table->index('confidence_score');
            $table->index('origin_dataset_name');
            $table->index('added_by_ai');
            $table->index('content_hash');

            // Fulltext indexes for text matching (Arabic support)
            // $table->fullText(['title', 'content'], 'fake_news_fulltext_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datasets_fake_news');
    }
};
