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
        Schema::create('cv_scans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('job_id')->constrained('jobs')->cascadeOnDelete();
            $table->string('status')->default('pending');
            $table->integer('match_score')->nullable();
            $table->jsonb('strengths')->nullable();
            $table->jsonb('weaknesses')->nullable();
            $table->jsonb('suggestions')->nullable();
            $table->string('ai_model_used')->nullable();
            $table->integer('tokens_used')->nullable();
            $table->integer('processing_time_ms')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            // Composite index for guest rate limiting by IP + time
            $table->index(['ip_address', 'created_at']);

            // Composite index for user scan history (descending created_at)
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_scans');
    }
};
