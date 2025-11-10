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
        Schema::create('legitimate_news', function (Blueprint $table) {
            $table->id();
            $table->string('title', 500);
            $table->longText('content');
            $table->string('source', 100)->index(); // MOJ, SPA, SUPREME_COURT, etc.
            $table->string('category', 50)->default('legal'); // legal, government, judicial
            $table->string('url', 1000)->nullable();
            $table->timestamp('publish_date')->nullable()->index();
            $table->decimal('credibility_score', 3, 2)->default(0.95); // 0.00 to 1.00
            $table->string('language', 10)->default('ar'); // ar, en
            $table->string('content_hash', 64)->unique(); // For duplicate detection
            $table->json('metadata')->nullable(); // Additional info (author, tags, etc.)
            $table->boolean('verified')->default(true); // All legitimate news are verified
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['source', 'publish_date']);
            $table->index(['category', 'created_at']);
            $table->index('credibility_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legitimate_news');
    }
};
