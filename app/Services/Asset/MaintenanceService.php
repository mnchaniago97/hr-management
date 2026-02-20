<?php

namespace App\Services\Asset;

use App\Models\Asset\AssetItem;
use App\Models\Asset\MaintenanceRecord;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class MaintenanceService
{
    /**
     * Get all maintenance records with filtering and pagination.
     */
    public function getAll(Request $request): LengthAwarePaginator
    {
        $query = MaintenanceRecord::with('item');

        if ($search = $request->get('search')) {
            $query->whereHas('item', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        if ($dateFrom = $request->get('date_from')) {
            $query->whereDate('maintenance_date', '>=', $dateFrom);
        }

        if ($dateTo = $request->get('date_to')) {
            $query->whereDate('maintenance_date', '<=', $dateTo);
        }

        return $query->orderBy('maintenance_date', 'desc')->paginate(15);
    }

    /**
     * Create a new maintenance record.
     */
    public function create(array $data): MaintenanceRecord
    {
        $record = MaintenanceRecord::create($data);

        // Update item status to maintenance if needed
        if ($data['status'] === 'in_progress' || $data['status'] === 'pending') {
            AssetItem::findOrFail($data['item_id'])->update(['status' => 'maintenance']);
        }

        return $record;
    }

    /**
     * Update an existing maintenance record.
     */
    public function update(MaintenanceRecord $record, array $data): MaintenanceRecord
    {
        $record->update($data);

        // Update item status based on maintenance completion
        if ($data['status'] === 'completed') {
            $record->item->update(['status' => 'available']);
        } elseif ($data['status'] === 'in_progress' || $data['status'] === 'pending') {
            $record->item->update(['status' => 'maintenance']);
        }

        return $record;
    }

    /**
     * Delete a maintenance record.
     */
    public function delete(MaintenanceRecord $record): void
    {
        // Restore item status if needed
        if ($record->item->status === 'maintenance') {
            $record->item->update(['status' => 'available']);
        }
        $record->delete();
    }

    /**
     * Get upcoming maintenance.
     */
    public function getUpcoming(): \Illuminate\Database\Eloquent\Collection
    {
        return MaintenanceRecord::with('item')
            ->where('status', 'pending')
            ->whereDate('scheduled_date', '>=', Carbon::now()->toDateString())
            ->orderBy('scheduled_date', 'asc')
            ->get();
    }

    /**
     * Get maintenance history for an item.
     */
    public function getByItem(int $itemId): \Illuminate\Database\Eloquent\Collection
    {
        return MaintenanceRecord::where('item_id', $itemId)
            ->orderBy('maintenance_date', 'desc')
            ->get();
    }

    /**
     * Calculate maintenance costs for an item.
     */
    public function getTotalCostByItem(int $itemId): float
    {
        return MaintenanceRecord::where('item_id', $itemId)
            ->where('status', 'completed')
            ->sum('cost');
    }
}
