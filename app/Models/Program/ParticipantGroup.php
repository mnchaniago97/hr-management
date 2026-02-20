<?php

namespace App\Models\Program;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParticipantGroup extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'program_participant_groups';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'activity_id',
        'group_type',
        'target_count',
        'actual_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'activity_id' => 'integer',
        'target_count' => 'integer',
        'actual_count' => 'integer',
    ];

    /**
     * Group types (PMI membership levels).
     */
    public const TYPE_ANGGOTA = 'anggota';           // Regular member
    public const TYPE_ANGGOTA_MUDA = 'AM';            // Anggota Muda (Young Member)
    public const TYPE_ANGGOTA_TER_SENIOR = 'AT';      // Anggota Terb Senior
    public const TYPE_CALON_ANGGOTA = 'calon_anggota'; // Prospective member
    public const TYPE_MASYARAKAT = 'masyarakat';      // General public

    /**
     * Get the activity that owns this participant group.
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    /**
     * Get all participants in this group.
     */
    public function participants(): HasMany
    {
        return $this->hasMany(\App\Models\Program\ActivityParticipant::class, 'group_id');
    }

    /**
     * Scope to get groups by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('group_type', $type);
    }

    /**
     * Scope to get groups for an activity.
     */
    public function scopeForActivity($query, int $activityId)
    {
        return $query->where('activity_id', $activityId);
    }

    /**
     * Calculate achievement percentage.
     */
    public function getAchievementPercentageAttribute(): float
    {
        if ($this->target_count === 0) {
            return 0;
        }

        return round(($this->actual_count / $this->target_count) * 100, 2);
    }

    /**
     * Check if target is achieved.
     */
    public function isTargetAchieved(): bool
    {
        return $this->actual_count >= $this->target_count;
    }

    /**
     * Get available group types.
     */
    public static function getAvailableTypes(): array
    {
        return [
            self::TYPE_ANGGOTA,
            self::TYPE_ANGGOTA_MUDA,
            self::TYPE_ANGGOTA_TER_SENIOR,
            self::TYPE_CALON_ANGGOTA,
            self::TYPE_MASYARAKAT,
        ];
    }

    /**
     * Get group type label.
     */
    public static function getTypeLabel(string $type): string
    {
        return match ($type) {
            self::TYPE_ANGGOTA => 'Anggota',
            self::TYPE_ANGGOTA_MUDA => 'Anggota Muda (AM)',
            self::TYPE_ANGGOTA_TER_SENIOR => 'Anggota Terb Senior (AT)',
            self::TYPE_CALON_ANGGOTA => 'Calon Anggota',
            self::TYPE_MASYARAKAT => 'Masyarakat',
            default => $type,
        };
    }
}
