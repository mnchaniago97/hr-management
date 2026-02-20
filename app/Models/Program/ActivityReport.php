<?php

namespace App\Models\Program;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityReport extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'program_activity_reports';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'activity_id',
        'title',
        'content',
        'output_achieved',
        'participant_count',
        'budget_used',
        'submitted_by',
        'submitted_at',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'activity_id' => 'integer',
        'output_achieved' => 'array',
        'participant_count' => 'integer',
        'budget_used' => 'decimal:2',
        'submitted_by' => 'integer',
        'submitted_at' => 'datetime',
    ];

    /**
     * Report statuses.
     */
    public const STATUS_DRAFT = 'draft';
    public const STATUS_SUBMITTED = 'submitted';
    public const STATUS_REVIEWED = 'reviewed';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    /**
     * Get the activity that owns this report.
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    /**
     * Get the user who submitted this report.
     */
    public function submitter(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'submitted_by');
    }

    /**
     * Scope to get reports for an activity.
     */
    public function scopeForActivity($query, int $activityId)
    {
        return $query->where('activity_id', $activityId);
    }

    /**
     * Scope to get reports by status.
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get submitted reports.
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', self::STATUS_SUBMITTED);
    }

    /**
     * Check if report is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Check if report is pending review.
     */
    public function isPendingReview(): bool
    {
        return in_array($this->status, [self::STATUS_SUBMITTED, self::STATUS_REVIEWED]);
    }

    /**
     * Get budget utilization percentage.
     */
    public function getBudgetUtilizationPercentageAttribute(?float $budgetAllocated): float
    {
        if (!$budgetAllocated || $budgetAllocated === 0) {
            return 0;
        }

        return round(($this->budget_used / $budgetAllocated) * 100, 2);
    }

    /**
     * Get output achievement summary.
     */
    public function getOutputSummaryAttribute(): string
    {
        if (empty($this->output_achieved)) {
            return 'No output recorded';
        }

        return collect($this->output_achieved)
            ->map(fn($value, $key) => "{$key}: {$value}")
            ->join(', ');
    }

    /**
     * Get available statuses.
     */
    public static function getAvailableStatuses(): array
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_SUBMITTED,
            self::STATUS_REVIEWED,
            self::STATUS_APPROVED,
            self::STATUS_REJECTED,
        ];
    }

    /**
     * Get status label.
     */
    public static function getStatusLabel(string $status): string
    {
        return match ($status) {
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_SUBMITTED => 'Submitted',
            self::STATUS_REVIEWED => 'Reviewed',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            default => $status,
        };
    }
}
