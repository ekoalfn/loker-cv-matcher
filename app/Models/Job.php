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
     * Flexible search across title, company, location, tags, summary, and description.
     * Typo-tolerant fallback ranking runs in JobRepository when exact DB matches are weak.
     *
     * @param  Builder<Job>  $query
     * @return Builder<Job>
     */
    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        $keyword = Str::of($keyword)->squish()->lower()->toString();
        $terms = collect(preg_split('/[^a-z0-9]+/i', $keyword) ?: [])
            ->map(fn (string $term): string => Str::lower($term))
            ->filter(fn (string $term): bool => strlen($term) >= 2)
            ->reject(fn (string $term): bool => in_array($term, ['di', 'job', 'jobs', 'karir', 'kerja', 'loker', 'lowongan', 'pt'], true))
            ->values();

        if ($terms->isEmpty()) {
            $terms = collect([$keyword]);
        }

        $driver = $query->getConnection()->getDriverName();
        $operator = $driver === 'pgsql' ? 'ILIKE' : 'LIKE';
        $searchColumns = ['title', 'company', 'location', 'summary_ai', 'description_raw'];

        $query->where(function (Builder $q) use ($driver, $keyword, $terms, $operator, $searchColumns): void {
            if ($driver === 'pgsql') {
                $q->whereRaw(
                    "to_tsvector('indonesian', coalesce(title,'') || ' ' || coalesce(company,'') || ' ' || coalesce(location,'') || ' ' || coalesce(summary_ai,'') || ' ' || coalesce(description_raw,'') || ' ' || coalesce(tags::text,'')) @@ websearch_to_tsquery('indonesian', ?)",
                    [$keyword]
                )
                    ->orWhereRaw('tags::text ILIKE ?', ['%' . $keyword . '%']);
            }

            foreach ($terms as $term) {
                $q->orWhere(function (Builder $termQuery) use ($driver, $operator, $searchColumns, $term): void {
                    foreach ($searchColumns as $column) {
                        $termQuery->orWhere($column, $operator, '%' . $term . '%');
                    }

                    if ($driver === 'pgsql') {
                        $termQuery->orWhereRaw('tags::text ILIKE ?', ['%' . $term . '%']);
                    }
                });
            }
        });

        return $query->orderByRaw(
            "CASE
                WHEN title {$operator} ? THEN 0
                WHEN company {$operator} ? THEN 1
                WHEN title {$operator} ? OR company {$operator} ? THEN 2
                WHEN location {$operator} ? THEN 3
                ELSE 4
            END",
            ["%{$keyword}%", "%{$keyword}%", '%' . $terms->first() . '%', '%' . $terms->first() . '%', '%' . $terms->first() . '%']
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
