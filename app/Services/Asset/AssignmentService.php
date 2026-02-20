<?php

namespace App\Services\Asset;

use App\Models\Asset\AssetAssignment;
use App\Models\Asset\AssetItem;
use App\Models\Hr\Member;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class AssignmentService
{
    /**
     * Get all assignments with filtering and pagination.
     */
    public function getAll(Request $request): LengthAwarePaginator
    {
        $query = AssetAssignment::with(['item', 'member']);

        if ($search = $request->get('search')) {
            $query->whereHas('member', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('item', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($dateFrom = $request->get('date_from')) {
            $query->whereDate('assignment_date', '>=', $dateFrom);
        }

        if ($dateTo = $request->get('date_to')) {
            $query->whereDate('assignment_date', '<=', $dateTo);
        }

        return $query->orderBy('assignment_date', 'desc')->paginate(15);
    }

    /**
     * Create a new assignment.
     */
    public function create(array $data): AssetAssignment
    {
        $item = AssetItem::findOrFail($data['item_id']);
        
        $assignment = AssetAssignment::create([
            'item_id' => $data['item_id'],
            'member_id' => $data['member_id'],
            'assignment_date' => $data['assignment_date'],
            'return_date' => $data['return_date'],
            'status' => 'borrowed',
            'notes' => $data['notes'] ?? null,
        ]);

        // Update item status
        $item->update(['status' => 'borrowed']);

        return $assignment;
    }

    /**
     * Mark item as returned.
     */
    public function return(AssetAssignment $assignment, array $data = []): AssetAssignment
    {
        $assignment->update([
            'actual_return_date' => Carbon::now(),
            'status' => 'returned',
            'notes' => $assignment->notes . "\n" . ($data['condition_notes'] ?? ''),
        ]);

        // Update item status back to available
        $assignment->item->update(['status' => 'available']);

        return $assignment;
    }

    /**
     * Extend assignment period.
     */
    public function extend(AssetAssignment $assignment, string $newReturnDate): AssetAssignment
    {
        $assignment->update(['return_date' => $newReturnDate]);
        return $assignment;
    }

    /**
     * Delete an assignment.
     */
    public function delete(AssetAssignment $assignment): void
    {
        if ($assignment->status === 'borrowed') {
            $assignment->item->update(['status' => 'available']);
        }
        $assignment->delete();
    }

    /**
     * Get overdue assignments.
     */
    public function getOverdue(): \Illuminate\Database\Eloquent\Collection
    {
        $today = Carbon::now()->toDateString();
        
        return AssetAssignment::with(['item', 'member'])
            ->where('status', 'borrowed')
            ->whereDate('return_date', '<', $today)
            ->orderBy('return_date', 'asc')
            ->get();
    }

    /**
     * Get active assignments for a member.
     */
    public function getActiveByMember(int $memberId): \Illuminate\Database\Eloquent\Collection
    {
        return AssetAssignment::with('item')
            ->where('member_id', $memberId)
            ->where('status', 'borrowed')
            ->get();
    }

    /**
     * Check if item is available for assignment.
     */
    public function isAvailable(int $itemId): bool
    {
        $item = AssetItem::findOrFail($itemId);
        return $item->status === 'available' && $item->condition !== 'poor';
    }
}
