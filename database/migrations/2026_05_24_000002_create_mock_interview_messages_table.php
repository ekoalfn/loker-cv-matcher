<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mock_interview_messages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('mock_interview_session_id')->constrained()->cascadeOnDelete();
            $table->string('role');
            $table->longText('content_text');
            $table->string('audio_path')->nullable();
            $table->unsignedInteger('audio_duration_seconds')->nullable();
            $table->unsignedTinyInteger('score')->nullable();
            $table->json('feedback')->nullable();
            $table->unsignedInteger('tokens_used')->default(0);
            $table->timestamps();

            $table->index(['mock_interview_session_id', 'created_at']);
            $table->index('role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mock_interview_messages');
    }
};
