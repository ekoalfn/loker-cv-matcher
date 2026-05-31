<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MockInterviewMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'mock_interview_session_id',
        'role',
        'content_text',
        'audio_path',
        'audio_duration_seconds',
        'score',
        'feedback',
        'tokens_used',
    ];

    protected function casts(): array
    {
        return [
            'feedback' => 'array',
        ];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(MockInterviewSession::class, 'mock_interview_session_id');
    }
}
