<?php

namespace App\Models;

use App\Enums\CvScanStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class CvScan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'job_id',
        'status',
        'match_score',
        'strengths',
        'weaknesses',
        'suggestions',
        'ai_model_used',
        'tokens_used',
        'processing_time_ms',
        'ip_address',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => CvScanStatus::class,
            'strengths' => 'array',
            'weaknesses' => 'array',
            'suggestions' => 'array',
            'match_score' => 'integer',
            'tokens_used' => 'integer',
            'processing_time_ms' => 'integer',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Job, $this>
     */
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /**
     * Scope to only completed scans.
     *
     * @param  Builder<CvScan>  $query
     * @return Builder<CvScan>
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', CvScanStatus::Completed);
    }

    /**
     * Scope to find scans from a specific IP address made today (for rate limiting).
     *
     * @param  Builder<CvScan>  $query
     * @return Builder<CvScan>
     */
    public function scopeForIpToday(Builder $query, string $ip): Builder
    {
        return $query->where('ip_address', $ip)
            ->where('created_at', '>=', Carbon::today());
    }
}
