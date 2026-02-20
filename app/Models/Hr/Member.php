<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Member extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hr_members';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'position',
        'department',
        'status',
        'photo',
        'member_type',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
        ];
    }

    /**
     * Get the attendances for the member.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the leave requests for the member.
     */
    public function leaveRequests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class);
    }

    /**
     * Get the item loans for the member.
     */
    public function itemLoans(): HasMany
    {
        return $this->hasMany(\App\Models\Asset\AssetAssignment::class, 'member_id');
    }

    /**
     * Get the training participants for the member.
     */
    public function trainingParticipants(): HasManyThrough
    {
        return $this->hasManyThrough(
            \App\Models\Program\TrainingParticipant::class,
            Employee::class,
            'member_id',
            'employee_id',
            'id',
            'id'
        );
    }

    /**
     * Get the profile record for the member.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Employee::class, 'member_id');
    }
}
