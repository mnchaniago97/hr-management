<?php

namespace App\Models\Program;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Period extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'program_periods';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'year_start',
        'year_end',
        'is_active',
        'start_date',
        'end_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'year_start' => 'integer',
        'year_end' => 'integer',
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get all fields for this period.
     */
    public function fields(): HasMany
    {
        return $this->hasMany(Field::class, 'period_id');
    }

    /**
     * Get all programs for this period.
     */
    public function programs(): HasMany
    {
        return $this->hasMany(Program::class, 'period_id');
    }

    /**
     * Scope to get active period.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the period name (e.g., "2026-2027").
     */
    public function getNameAttribute(): string
    {
        return "{$this->year_start}-{$this->year_end}";
    }
}
