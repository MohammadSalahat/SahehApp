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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('article_title', 500);
            $table->tinyInteger('rating')->unsigned()->comment('Rating from 1 to 5');
            $table->text('message')->nullable();
            $table->string('verification_result', 50)->nullable()->comment('e.g., fake, genuine, uncertain');
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('rating');
            $table->index('created_at');
            $table->index('verification_result');

            // Fulltext index for article title search
            $table->fullText('article_title', 'feedback_article_title_fulltext');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
