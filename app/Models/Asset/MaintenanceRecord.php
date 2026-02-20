<?php

namespace App\Models\Asset;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceRecord extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'asset_maintenance_records';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'item_id',
        'vendor_id',
        'type',
        'maintenance_date',
        'scheduled_date',
        'status',
        'cost',
        'description',
        'performed_by',
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
            'maintenance_date' => 'date',
            'scheduled_date' => 'date',
            'cost' => 'decimal:2',
        ];
    }

    /**
     * Get the item that owns the maintenance record.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(AssetItem::class);
    }

    /**
     * Get the vendor for this maintenance record.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
