<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MockInterviewSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_id',
        'session_token',
        'ip_address',
        'target_role',
        'interview_mode',
        'delivery_mode',
        'status',
        'current_question_count',
        'max_questions',
        'cv_text_snapshot',
        'profile_summary',
        'final_feedback',
        'ai_model_used',
        'tokens_used',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'profile_summary' => 'array',
            'final_feedback' => 'array',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(MockInterviewMessage::class);
    }
}
