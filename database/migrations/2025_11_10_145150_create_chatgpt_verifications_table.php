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
        Schema::create('chatgpt_verifications', function (Blueprint $table) {
            $table->id();
            $table->text('original_text')->comment('The news text that was verified');
            $table->string('language', 2)->default('ar')->comment('ar or en');
            $table->string('category')->nullable()->comment('legal, health, financial, etc.');
            $table->string('model_used')->default('gpt-4')->comment('ChatGPT model used');
            
            // Verification Results
            $table->boolean('is_potentially_fake')->default(false);
            $table->decimal('confidence_score', 5, 4)->default(0.5)->comment('0.0 to 1.0');
            $table->string('credibility_level')->default('medium')->comment('high, medium, low, very_low');
            
            // Analysis Data (JSON)
            $table->json('analysis')->nullable()->comment('Detailed analysis in both languages');
            $table->json('warning_signs')->nullable()->comment('Array of warning signs');
            $table->json('recommendation')->nullable()->comment('Recommendations in both languages');
            $table->json('verification_tips')->nullable()->comment('Tips for verification');
            $table->json('related_topics')->nullable()->comment('Related topics array');
            $table->json('fact_check_sources')->nullable()->comment('Recommended fact-check sources');
            
            // Meta Information
            $table->integer('tokens_used')->default(0)->comment('OpenAI tokens consumed');
            $table->integer('processing_time_ms')->nullable()->comment('Processing time in milliseconds');
            $table->ipAddress('user_ip')->nullable()->comment('User IP address');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->comment('User who requested verification');
            
            // Status
            $table->enum('status', ['pending', 'completed', 'failed'])->default('completed');
            $table->text('error_message')->nullable()->comment('Error message if failed');
            
            $table->timestamps();
            
            // Indexes
            $table->index('language');
            $table->index('category');
            $table->index('is_potentially_fake');
            $table->index('credibility_level');
            $table->index('created_at');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatgpt_verifications');
    }
};
