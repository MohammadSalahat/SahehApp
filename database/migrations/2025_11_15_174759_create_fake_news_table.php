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
        Schema::create('fake_news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('content');
            $table->enum('language', ['ar', 'en', 'mixed'])->default('ar');
            $table->enum('label', ['fake', 'real'])->default('fake');
            $table->string('category')->nullable(); // religious, legal, political, etc.
            $table->string('source')->nullable(); // source location or platform
            $table->string('region')->default('ksa'); // geographical region
            $table->decimal('confidence_score', 5, 4)->default(0.0000); // AI confidence score
            $table->string('origin_dataset_name')->nullable(); // which dataset this came from
            $table->boolean('is_ksa_specific')->default(true); // KSA-specific flag
            $table->json('metadata')->nullable(); // additional data like keywords, etc.
            $table->string('content_hash')->unique()->nullable(); // prevent duplicates
            $table->timestamp('detected_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for better performance
            $table->index(['label', 'language']);
            $table->index(['is_ksa_specific', 'region']);
            $table->index('confidence_score');
            $table->index('origin_dataset_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fake_news');
    }
};
