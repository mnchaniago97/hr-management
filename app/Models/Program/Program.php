<?php

namespace App\Models\Program;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'programs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'period_id',
        'field_id',
        'division_id',
        'name',
        'type',
        'description',
        'target',
        'budget',
        'status',
        'start_date',
        'end_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'period_id' => 'integer',
        'field_id' => 'integer',
        'division_id' => 'integer',
        'target' => 'integer',
        'budget' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Program types.
     */
    public const TYPE_TERPROGRAM = 'terprogram';
    public const TYPE_INSIDENTIL = 'insidentil';

    /**
     * Program statuses.
     */
    public const STATUS_DRAFT = 'draft';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_ONGOING = 'ongoing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the period that owns this program.
     */
    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class, 'period_id');
    }

    /**
     * Get the field that owns this program.
     */
    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class, 'field_id');
    }

    /**
     * Get the division that owns this program.
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    /**
     * Get all activities for this program.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'program_id');
    }

    /**
     * Get all documents for this program.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'program_id');
    }

    /**
     * Scope to get programs by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to get programs by status.
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get programs by field.
     */
    public function scopeForField($query, int $fieldId)
    {
        return $query->where('field_id', $fieldId);
    }

    /**
     * Check if program is terprogram.
     */
    public function isTerprogram(): bool
    {
        return $this->type === self::TYPE_TERPROGRAM;
    }

    /**
     * Check if program is insidentil.
     */
    public function isInsidentil(): bool
    {
        return $this->type === self::TYPE_INSIDENTIL;
    }

    /**
     * Get available types.
     */
    public static function getAvailableTypes(): array
    {
        return [
            self::TYPE_TERPROGRAM,
            self::TYPE_INSIDENTIL,
        ];
    }

    /**
     * Get available statuses.
     */
    public static function getAvailableStatuses(): array
    {
        return [
            self::STATUS_DRAFT,
            self::STATUS_APPROVED,
            self::STATUS_ONGOING,
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED,
        ];
    }
}
