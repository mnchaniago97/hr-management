<?php

namespace App\Models\Program;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Division extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'program_divisions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'field_id',
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
        'field_id' => 'integer',
        'head_id' => 'integer',
    ];

    /**
     * Get the field that owns this division.
     */
    public function field(): BelongsTo
    {
        return $this->belongsTo(Field::class, 'field_id');
    }

    /**
     * Get all activities for this division.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'division_id');
    }

    /**
     * Get all programs for this division.
     */
    public function programs(): HasMany
    {
        return $this->hasMany(Program::class, 'division_id');
    }

    /**
     * Get the head of this division.
     */
    public function head()
    {
        return $this->belongsTo(\App\Models\Hr\Member::class, 'head_id');
    }

    /**
     * Scope to get divisions by field.
     */
    public function scopeForField($query, int $fieldId)
    {
        return $query->where('field_id', $fieldId);
    }
}
