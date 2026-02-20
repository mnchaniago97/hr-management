<?php

namespace App\Models\Program;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Field extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'program_fields';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'period_id',
        'name',
        'code',
        'description',
        'head_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'period_id' => 'integer',
        'head_id' => 'integer',
    ];

    /**
     * Get the period that owns this field.
     */
    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class, 'period_id');
    }

    /**
     * Get all divisions for this field.
     */
    public function divisions(): HasMany
    {
        return $this->hasMany(Division::class, 'field_id');
    }

    /**
     * Get all programs for this field.
     */
    public function programs(): HasMany
    {
        return $this->hasMany(Program::class, 'field_id');
    }

    /**
     * Get the head of this field.
     */
    public function head()
    {
        return $this->belongsTo(\App\Models\Hr\Member::class, 'head_id');
    }

    /**
     * Scope to get fields by period.
     */
    public function scopeForPeriod($query, int $periodId)
    {
        return $query->where('period_id', $periodId);
    }

    /**
     * Get available codes for PMI fields.
     */
    public static function getAvailableCodes(): array
    {
        return ['BIDANG_1', 'BIDANG_2', 'BIDANG_3'];
    }
}
