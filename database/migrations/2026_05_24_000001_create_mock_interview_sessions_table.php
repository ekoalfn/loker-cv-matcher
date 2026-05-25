<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mock_interview_sessions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('job_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_token', 80)->unique();
            $table->string('ip_address', 45)->nullable();
            $table->string('target_role')->nullable();
            $table->string('interview_mode')->default('mixed');
            $table->string('delivery_mode')->default('voice');
            $table->string('status')->default('analyzing');
            $table->unsignedTinyInteger('current_question_count')->default(0);
            $table->unsignedTinyInteger('max_questions')->default(6);
            $table->longText('cv_text_snapshot')->nullable();
            $table->json('profile_summary')->nullable();
            $table->json('final_feedback')->nullable();
            $table->string('ai_model_used')->nullable();
            $table->unsignedInteger('tokens_used')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['ip_address', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mock_interview_sessions');
    }
};
