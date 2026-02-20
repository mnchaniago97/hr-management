<?php

namespace App\Models\Program;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'program_activities';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'program_id',
        'division_id',
        'name',
        'description',
        'type',
        'start_date',
        'end_date',
        'location',
        'capacity',
        'registered_count',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'program_id' => 'integer',
        'division_id' => 'integer',
        'capacity' => 'integer',
        'registered_count' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Activity types (common PMI activities).
     */
    public const TYPE_LKMR = 'lkmr';       // Latihan Keterampilan Mahasiswa Baru
    public const TYPE_OSCAB = 'oscab';     // Oscar Banking
    public const TYPE_DIKLATSAR = 'diklatsar'; // Pelatihan Dasar
    public const TYPE_DONOR_DARAH = 'donor_darah';
    public const TYPE_BANTUAN_KEMANUSIAAN = 'bantuan_kemanusiaan';
    public const TYPE_PELATIHAN = 'pelatihan';
    public const TYPE_SEMINAR = 'seminar';
    public const TYPE_SOSIALISASI = 'sosialisasi';
    public const TYPE_LAINNYA = 'lainnya';

    /**
     * Activity statuses.
     */
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_REGISTRATION_OPEN = 'registration_open';
    public const STATUS_REGISTRATION_CLOSED = 'registration_closed';
    public const STATUS_ONGOING = 'ongoing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the program that owns this activity.
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    /**
     * Get the division that owns this activity.
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    /**
     * Get all schedules for this activity.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'activity_id');
    }

    /**
     * Get all participant groups for this activity.
     */
    public function participantGroups(): HasMany
    {
        return $this->hasMany(ParticipantGroup::class, 'activity_id');
    }

    /**
     * Get all documents for this activity.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'activity_id');
    }

    /**
     * Get all reports for this activity.
     */
    public function reports(): HasMany
    {
        return $this->hasMany(ActivityReport::class, 'activity_id');
    }

    /**
     * Scope to get activities by program.
     */
    public function scopeForProgram($query, int $programId)
    {
        return $query->where('program_id', $programId);
    }

    /**
     * Scope to get activities by division.
     */
    public function scopeForDivision($query, int $divisionId)
    {
        return $query->where('division_id', $divisionId);
    }

    /**
     * Scope to get activities by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to get activities by status.
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Check if registration is open.
     */
    public function isRegistrationOpen(): bool
    {
        return $this->status === self::STATUS_REGISTRATION_OPEN;
    }

    /**
     * Check if activity is ongoing.
     */
    public function isOngoing(): bool
    {
        return $this->status === self::STATUS_ONGOING;
    }

    /**
     * Get available types.
     */
    public static function getAvailableTypes(): array
    {
        return [
            self::TYPE_LKMR,
            self::TYPE_OSCAB,
            self::TYPE_DIKLATSAR,
            self::TYPE_DONOR_DARAH,
            self::TYPE_BANTUAN_KEMANUSIAAN,
            self::TYPE_PELATIHAN,
            self::TYPE_SEMINAR,
            self::TYPE_SOSIALISASI,
            self::TYPE_LAINNYA,
        ];
    }

    /**
     * Get available statuses.
     */
    public static function getAvailableStatuses(): array
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_PUBLISHED,
            self::STATUS_REGISTRATION_OPEN,
            self::STATUS_REGISTRATION_CLOSED,
            self::STATUS_ONGOING,
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED,
        ];
    }
}
