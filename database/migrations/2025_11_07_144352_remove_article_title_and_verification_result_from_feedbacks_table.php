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
        Schema::table('feedbacks', function (Blueprint $table) {
            // Drop the columns and their indexes
            $table->dropIndex(['verification_result']);
            $table->dropColumn(['article_title', 'verification_result']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            // Restore the columns
            $table->string('article_title')->after('user_id');
            $table->string('verification_result', 50)->nullable()->comment('e.g., fake, genuine, uncertain')->after('message');

            // Restore the index
            $table->index('verification_result');
        });
    }
};
