<?php

namespace App\Services\Hr;

use App\Models\Hr\Position;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PositionService
{
    /**
     * Get all positions with filtering and pagination.
     */
    public function getAll(Request $request): LengthAwarePaginator
    {
        $query = Position::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($department = $request->get('department')) {
            $query->where('department', $department);
        }

        if ($level = $request->get('level')) {
            $query->where('level', $level);
        }

        return $query->orderBy('level', 'asc')->orderBy('name', 'asc')->paginate(15);
    }

    /**
     * Create a new position.
     */
    public function create(array $data): Position
    {
        return Position::create($data);
    }

    /**
     * Update an existing position.
     */
    public function update(Position $position, array $data): Position
    {
        $position->update($data);
        return $position;
    }

    /**
     * Delete a position.
     */
    public function delete(Position $position): bool
    {
        if ($position->members()->count() > 0) {
            return false;
        }
        $position->delete();
        return true;
    }

    /**
     * Get positions by level.
     */
    public function getByLevel(int $level): \Illuminate\Database\Eloquent\Collection
    {
        return Position::where('level', $level)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get active positions.
     */
    public function getActive(): \Illuminate\Database\Eloquent\Collection
    {
        return Position::where('is_active', true)
            ->orderBy('level', 'asc')
            ->orderBy('name', 'asc')
            ->get();
    }
}
