<?php

namespace App\Services\Program;

use App\Models\Program\Activity;
use App\Models\Program\Division;
use App\Models\Program\ParticipantGroup;
use App\Models\Program\Program;
use App\Models\Program\Schedule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ActivityService
{
    /**
     * Get all activities with optional filtering.
     */
    public function getAll(array $filters = []): Collection|LengthAwarePaginator
    {
        $query = Activity::query()->with(['program', 'division', 'schedules']);

        if (isset($filters['program_id'])) {
            $query->forProgram($filters['program_id']);
        }

        if (isset($filters['division_id'])) {
            $query->forDivision($filters['division_id']);
        }

        if (isset($filters['type'])) {
            $query->ofType($filters['type']);
        }

        if (isset($filters['status'])) {
            $query->withStatus($filters['status']);
        }

        if (isset($filters['field_id'])) {
            $query->whereHas('program', function ($q) use ($filters) {
                $q->where('field_id', $filters['field_id']);
            });
        }

        if (isset($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (isset($filters['start_date_from'])) {
            $query->where('start_date', '>=', $filters['start_date_from']);
        }

        if (isset($filters['start_date_to'])) {
            $query->where('start_date', '<=', $filters['start_date_to']);
        }

        $sortBy = $filters['sort_by'] ?? 'start_date';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        if (isset($filters['per_page'])) {
            return $query->paginate($filters['per_page']);
        }

        return $query->get();
    }

    /**
     * Get activity by ID.
     */
    public function getById(int $id): ?Activity
    {
        return Activity::with([
            'program.field',
            'division',
            'schedules',
            'participantGroups',
            'documents',
            'reports',
        ])->find($id);
    }

    /**
     * Create a new activity.
     */
    public function create(array $data): Activity
    {
        $data['status'] = $data['status'] ?? Activity::STATUS_DRAFT;
        $data['registered_count'] = $data['registered_count'] ?? 0;

        $activity = Activity::create($data);

        return $activity->load(['program', 'division', 'schedules']);
    }

    /**
     * Update an existing activity.
     */
    public function update(Activity $activity, array $data): Activity
    {
        $activity->update($data);

        return $activity->fresh(['program', 'division', 'schedules', 'participantGroups', 'documents']);
    }

    /**
     * Delete an activity.
     */
    public function delete(Activity $activity): bool
    {
        // Delete related schedules
        $activity->schedules()->delete();

        // Delete related participant groups
        $activity->participantGroups()->delete();

        // Note: Documents and reports should be handled separately or with cascade

        return $activity->delete();
    }

    /**
     * Update activity status.
     */
    public function updateStatus(Activity $activity, string $status): Activity
    {
        $activity->update(['status' => $status]);

        return $activity->fresh();
    }

    /**
     * Add a schedule to an activity.
     */
    public function addSchedule(Activity $activity, array $data): Schedule
    {
        return $activity->schedules()->create($data);
    }

    /**
     * Remove a schedule from an activity.
     */
    public function removeSchedule(Schedule $schedule): bool
    {
        return $schedule->delete();
    }

    /**
     * Add a participant group to an activity.
     */
    public function addParticipantGroup(Activity $activity, array $data): ParticipantGroup
    {
        return $activity->participantGroups()->create($data);
    }

    /**
     * Update participant count for an activity.
     */
    public function updateParticipantCount(Activity $activity, int $count): Activity
    {
        $activity->update(['registered_count' => $count]);

        return $activity->fresh();
    }

    /**
     * Get activities by program.
     */
    public function getByProgram(Program $program): Collection
    {
        return Activity::forProgram($program->id)
            ->with(['division', 'schedules', 'participantGroups'])
            ->orderBy('start_date', 'desc')
            ->get();
    }

    /**
     * Get activities by division.
     */
    public function getByDivision(Division $division): Collection
    {
        return Activity::forDivision($division->id)
            ->with(['program', 'schedules', 'participantGroups'])
            ->orderBy('start_date', 'desc')
            ->get();
    }

    /**
     * Get upcoming activities.
     */
    public function getUpcoming(int $limit = 10): Collection
    {
        return Activity::with(['program', 'division'])
            ->where('start_date', '>=', now()->toDateString())
            ->whereIn('status', [
                Activity::STATUS_PUBLISHED,
                Activity::STATUS_REGISTRATION_OPEN,
                Activity::STATUS_ONGOING,
            ])
            ->orderBy('start_date')
            ->limit($limit)
            ->get();
    }

    /**
     * Get ongoing activities.
     */
    public function getOngoing(): Collection
    {
        return Activity::with(['program', 'division'])
            ->where('status', Activity::STATUS_ONGOING)
            ->orderBy('start_date')
            ->get();
    }

    /**
     * Get activity statistics.
     */
    public function getStatistics(array $filters = []): array
    {
        $query = Activity::query();

        if (isset($filters['program_id'])) {
            $query->forProgram($filters['program_id']);
        }

        if (isset($filters['division_id'])) {
            $query->forDivision($filters['division_id']);
        }

        if (isset($filters['field_id'])) {
            $query->whereHas('program', function ($q) use ($filters) {
                $q->where('field_id', $filters['field_id']);
            });
        }

        return [
            'total' => $query->count(),
            'draft' => $query->clone()->withStatus(Activity::STATUS_DRAFT)->count(),
            'published' => $query->clone()->withStatus(Activity::STATUS_PUBLISHED)->count(),
            'registration_open' => $query->clone()->withStatus(Activity::STATUS_REGISTRATION_OPEN)->count(),
            'ongoing' => $query->clone()->withStatus(Activity::STATUS_ONGOING)->count(),
            'completed' => $query->clone()->withStatus(Activity::STATUS_COMPLETED)->count(),
            'cancelled' => $query->clone()->withStatus(Activity::STATUS_CANCELLED)->count(),
            'total_capacity' => $query->clone()->sum('capacity'),
            'total_registered' => $query->clone()->sum('registered_count'),
        ];
    }

    /**
     * Open registration for an activity.
     */
    public function openRegistration(Activity $activity): Activity
    {
        return $this->updateStatus($activity, Activity::STATUS_REGISTRATION_OPEN);
    }

    /**
     * Close registration for an activity.
     */
    public function closeRegistration(Activity $activity): Activity
    {
        return $this->updateStatus($activity, Activity::STATUS_REGISTRATION_CLOSED);
    }

    /**
     * Start an activity.
     */
    public function start(Activity $activity): Activity
    {
        return $this->updateStatus($activity, Activity::STATUS_ONGOING);
    }

    /**
     * Complete an activity.
     */
    public function complete(Activity $activity): Activity
    {
        return $this->updateStatus($activity, Activity::STATUS_COMPLETED);
    }

    /**
     * Cancel an activity.
     */
    public function cancel(Activity $activity): Activity
    {
        return $this->updateStatus($activity, Activity::STATUS_CANCELLED);
    }
}
