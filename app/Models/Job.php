<?php

namespace App\Models;

use App\Enums\EmploymentType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Job extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * Explicitly set to avoid any ambiguity with Laravel's default queue jobs table.
     */
    protected $table = 'jobs';

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'source_id',
        'title',
        'slug',
        'company',
        'location',
        'employment_type',
        'salary_min',
        'salary_max',
        'salary_currency',
        'description_raw',
        'summary_ai',
        'tags',
        'source_url',
        'is_active',
        'expires_at',
        'scraped_at',
        'detail_fetched_at',
        'detail_fetch_error',
        'company_logo',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'is_active' => 'boolean',
            'expires_at' => 'datetime',
            'scraped_at' => 'datetime',
            'detail_fetched_at' => 'datetime',
            'employment_type' => EmploymentType::class,
        ];
    }

    // -------------------------------------------------------------------------
    // Boot
    // -------------------------------------------------------------------------

    protected static function booted(): void
    {
        static::creating(function (Job $job): void {
            if (empty($job->slug)) {
                $base = Str::slug($job->title . ' ' . $job->company);
                $job->slug = $base . '-' . Str::lower(Str::random(6));
            }
        });
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /**
     * @return BelongsTo<JobSource, $this>
     */
    public function source(): BelongsTo
    {
        return $this->belongsTo(JobSource::class, 'source_id');
    }

    /**
     * @return HasMany<CvScan, $this>
     */
    public function cvScans(): HasMany
    {
        return $this->hasMany(CvScan::class);
    }

    // -------------------------------------------------------------------------
    // Scopes
    // -------------------------------------------------------------------------

    /**
     * Scope to only active, non-expired jobs.
     *
     * @param  Builder<Job>  $query
     * @return Builder<Job>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(function (Builder $q): void {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * PostgreSQL full-text search across title, company, and location.
     *
     * @param  Builder<Job>  $query
     * @return Builder<Job>
     */
    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->whereRaw(
            "to_tsvector('indonesian', coalesce(title,'') || ' ' || coalesce(company,'') || ' ' || coalesce(location,'')) @@ plainto_tsquery('indonesian', ?)",
            [$keyword]
        );
    }

    /**
     * Scope to filter jobs by location (case-insensitive partial match).
     * Supports both string and array of locations.
     *
     * @param  Builder<Job>  $query
     * @param  string|array<string>  $location
     * @return Builder<Job>
     */
    public function scopeInLocation(Builder $query, string|array $location): Builder
    {
        if (is_array($location)) {
            return $query->where(function (Builder $q) use ($location): void {
                foreach ($location as $loc) {
                    $q->orWhere('location', 'ILIKE', '%' . $loc . '%');
                }
            });
        }

        return $query->where('location', 'ILIKE', '%' . $location . '%');
    }
}
