<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetAssignment extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'asset_assignments';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'item_id',
        'member_id',
        'borrower_name',
        'borrower_institution',
        'borrower_phone',
        'borrower_address',
        'request_letter_path',
        'id_card_path',
        'assignment_date',
        'return_date',
        'actual_return_date',
        'status',
        'notes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'assignment_date' => 'date',
            'return_date' => 'date',
            'actual_return_date' => 'date',
        ];
    }

    /**
     * Get the item that owns the assignment.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(AssetItem::class);
    }

    /**
     * Get the member that owns the assignment.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Hr\Member::class);
    }
}
