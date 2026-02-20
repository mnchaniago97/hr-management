<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'requester_name',
        'institution',
        'phone',
        'email',
        'location',
        'event_date',
        'start_time',
        'end_time',
        'participants',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
        ];
    }
}
