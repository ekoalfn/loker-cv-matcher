<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\SubscriptionStatus;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    private const DEFAULT_FREE_SCAN_LIMIT = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'provider',
        'provider_id',
        'daily_scan_count',
        'last_scan_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_scan_date' => 'date',
        ];
    }

    // -------------------------------------------------------------------------
    // Relationships
    // -------------------------------------------------------------------------

    /**
     * @return HasMany<CvScan, $this>
     */
    public function cvScans(): HasMany
    {
        return $this->hasMany(CvScan::class);
    }

    /**
     * @return HasMany<Subscription, $this>
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    // -------------------------------------------------------------------------
    // Domain Methods
    // -------------------------------------------------------------------------

    /**
     * Check whether the user is allowed to perform a CV scan today.
     *
     * Premium users respect their plan's daily limit (null = unlimited).
     * Free users are capped at DEFAULT_FREE_SCAN_LIMIT per day.
     */
    public function canScanToday(): bool
    {
        $today = Carbon::today();

        // If the last scan was on a different day the counter is effectively 0.
        $scansToday = $this->last_scan_date?->isSameDay($today)
            ? $this->daily_scan_count
            : 0;

        $activeSubscription = $this->subscriptions()
            ->active()
            ->with('plan')
            ->first();

        if ($activeSubscription) {
            $limit = $activeSubscription->plan->scan_limit_daily;

            // null means unlimited scans.
            return $limit === null || $scansToday < $limit;
        }

        return $scansToday < self::DEFAULT_FREE_SCAN_LIMIT;
    }

    /**
     * Increment the daily scan counter, resetting it first if the day changed.
     */
    public function incrementScanCount(): void
    {
        $today = Carbon::today();

        if (! $this->last_scan_date?->isSameDay($today)) {
            $this->daily_scan_count = 0;
        }

        $this->daily_scan_count++;
        $this->last_scan_date = $today;
        $this->save();
    }

    /**
     * Determine whether the user holds an active (premium) subscription.
     */
    public function isPremium(): bool
    {
        return $this->subscriptions()
            ->active()
            ->exists();
    }
}
