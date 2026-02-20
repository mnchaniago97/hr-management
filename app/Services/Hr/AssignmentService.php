<?php

namespace App\Services\Hr;

use App\Models\Hr\DivisionAssignment;
use App\Models\Hr\Member;
use App\Models\Program\Division;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AssignmentService
{
    /**
     * Get all assignments with filtering and pagination.
     */
    public function getAll(Request $request): LengthAwarePaginator
    {
        $query = DivisionAssignment::with(['member', 'position', 'division']);

        if ($search = $request->get('search')) {
            $query->whereHas('member', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($divisionId = $request->get('division_id')) {
            $query->where('division_id', $divisionId);
        }

        return $query->orderBy('assigned_date', 'desc')->paginate(15);
    }

    /**
     * Create a new assignment.
     */
    public function create(array $data): DivisionAssignment
    {
        return DivisionAssignment::create($data);
    }

    /**
     * Update an existing assignment.
     */
    public function update(DivisionAssignment $assignment, array $data): DivisionAssignment
    {
        $assignment->update($data);
        return $assignment;
    }

    /**
     * Delete an assignment.
     */
    public function delete(DivisionAssignment $assignment): void
    {
        $assignment->delete();
    }

    /**
     * Transfer member to another division.
     */
    public function transfer(DivisionAssignment $assignment, array $data): DivisionAssignment
    {
        // Complete current assignment
        $assignment->update([
            'status' => 'transferred',
            'end_date' => now()->toDateString(),
        ]);

        // Create new assignment
        return DivisionAssignment::create([
            'member_id' => $assignment->member_id,
            'position_id' => $data['new_position_id'],
            'division_id' => $data['new_division_id'],
            'assigned_date' => now()->toDateString(),
            'status' => 'active',
            'notes' => $data['notes'] ?? null,
        ]);
    }

    /**
     * Get active assignments for a member.
     */
    public function getActiveByMember(int $memberId): \Illuminate\Database\Eloquent\Collection
    {
        return DivisionAssignment::with(['position', 'division'])
            ->where('member_id', $memberId)
            ->where('status', 'active')
            ->get();
    }

    /**
     * Get active assignments for a division.
     */
    public function getActiveByDivision(int $divisionId): \Illuminate\Database\Eloquent\Collection
    {
        return DivisionAssignment::with(['member', 'position'])
            ->where('division_id', $divisionId)
            ->where('status', 'active')
            ->get();
    }

    /**
     * Check if member has active assignment in division.
     */
    public function hasActiveAssignmentInDivision(int $memberId, int $divisionId): bool
    {
        return DivisionAssignment::where('member_id', $memberId)
            ->where('division_id', $divisionId)
            ->where('status', 'active')
            ->exists();
    }
}
