<?php

namespace App\Services\Program;

use App\Models\Program\Field;
use App\Models\Program\Period;
use App\Models\Program\Program;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProgramService
{
    /**
     * Get all programs with optional filtering.
     */
    public function getAll(array $filters = []): Collection|LengthAwarePaginator
    {
        $query = Program::query()->with(['field', 'activities']);

        if (isset($filters['field_id'])) {
            $query->forField($filters['field_id']);
        }

        if (isset($filters['type'])) {
            $query->ofType($filters['type']);
        }

        if (isset($filters['status'])) {
            $query->withStatus($filters['status']);
        }

        if (isset($filters['period_id'])) {
            $query->whereHas('field', function ($q) use ($filters) {
                $q->where('period_id', $filters['period_id']);
            });
        }

        if (isset($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        if (isset($filters['per_page'])) {
            return $query->paginate($filters['per_page']);
        }

        return $query->get();
    }

    /**
     * Get program by ID.
     */
    public function getById(int $id): ?Program
    {
        return Program::with(['field.period', 'activities', 'documents'])->find($id);
    }

    /**
     * Create a new program.
     */
    public function create(array $data): Program
    {
        $data['status'] = $data['status'] ?? Program::STATUS_DRAFT;

        $program = Program::create($data);

        return $program->load(['field', 'activities']);
    }

    /**
     * Update an existing program.
     */
    public function update(Program $program, array $data): Program
    {
        $program->update($data);

        return $program->fresh(['field', 'activities', 'documents']);
    }

    /**
     * Delete a program.
     */
    public function delete(Program $program): bool
    {
        // Check if program has activities
        if ($program->activities()->count() > 0) {
            return false;
        }

        return $program->delete();
    }

    /**
     * Update program status.
     */
    public function updateStatus(Program $program, string $status): Program
    {
        $program->update(['status' => $status]);

        return $program->fresh();
    }

    /**
     * Get programs by field.
     */
    public function getByField(Field $field): Collection
    {
        return Program::forField($field->id)
            ->with(['activities'])
            ->orderBy('name')
            ->get();
    }

    /**
     * Get programs by period.
     */
    public function getByPeriod(Period $period): Collection
    {
        return Program::whereHas('field', function ($query) use ($period) {
            $query->where('period_id', $period->id);
        })
            ->with(['field', 'activities'])
            ->orderBy('name')
            ->get();
    }

    /**
     * Get program statistics.
     */
    public function getStatistics(?Period $period = null): array
    {
        $query = Program::query();

        if ($period) {
            $query->whereHas('field', function ($q) use ($period) {
                $q->where('period_id', $period->id);
            });
        }

        return [
            'total' => $query->count(),
            'terprogram' => $query->clone()->ofType(Program::TYPE_TERPROGRAM)->count(),
            'insidentil' => $query->clone()->ofType(Program::TYPE_INSIDENTIL)->count(),
            'draft' => $query->clone()->withStatus(Program::STATUS_DRAFT)->count(),
            'approved' => $query->clone()->withStatus(Program::STATUS_APPROVED)->count(),
            'ongoing' => $query->clone()->withStatus(Program::STATUS_ONGOING)->count(),
            'completed' => $query->clone()->withStatus(Program::STATUS_COMPLETED)->count(),
            'total_budget' => $query->clone()->sum('budget'),
        ];
    }

    /**
     * Get programs that need attention (draft or overdue).
     */
    public function getProgramsNeedingAttention(): Collection
    {
        return Program::where(function ($query) {
            $query->where('status', Program::STATUS_DRAFT)
                ->orWhere(function ($q) {
                    $q->where('status', Program::STATUS_APPROVED)
                        ->where('end_date', '<', now()->toDateString());
                });
        })
            ->with(['field'])
            ->orderBy('end_date')
            ->get();
    }

    /**
     * Approve a program.
     */
    public function approve(Program $program): Program
    {
        return $this->updateStatus($program, Program::STATUS_APPROVED);
    }

    /**
     * Mark a program as completed.
     */
    public function complete(Program $program): Program
    {
        return $this->updateStatus($program, Program::STATUS_COMPLETED);
    }

    /**
     * Cancel a program.
     */
    public function cancel(Program $program): Program
    {
        return $this->updateStatus($program, Program::STATUS_CANCELLED);
    }
}
