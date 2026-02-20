<?php

namespace App\Services\Program;

use App\Models\Program\Activity;
use App\Models\Program\ActivityReport;
use App\Models\Program\Program;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportService
{
    /**
     * Get all reports with optional filtering.
     */
    public function getAll(array $filters = []): Collection|LengthAwarePaginator
    {
        $query = ActivityReport::query()->with(['activity', 'submitter']);

        if (isset($filters['activity_id'])) {
            $query->forActivity($filters['activity_id']);
        }

        if (isset($filters['status'])) {
            $query->withStatus($filters['status']);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('content', 'like', '%' . $filters['search'] . '%');
            });
        }

        $sortBy = $filters['sort_by'] ?? 'submitted_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        if (isset($filters['per_page'])) {
            return $query->paginate($filters['per_page']);
        }

        return $query->get();
    }

    /**
     * Get report by ID.
     */
    public function getById(int $id): ?ActivityReport
    {
        return ActivityReport::with(['activity.program.field', 'submitter'])->find($id);
    }

    /**
     * Create a new report.
     */
    public function create(array $data): ActivityReport
    {
        $data['status'] = $data['status'] ?? ActivityReport::STATUS_DRAFT;
        $data['submitted_by'] = $data['submitted_by'] ?? auth()->id();
        $data['submitted_at'] = $data['submitted_at'] ?? now();

        // Convert output_achieved array to JSON if provided
        if (isset($data['output_achieved']) && is_array($data['output_achieved'])) {
            $data['output_achieved'] = json_encode($data['output_achieved']);
        }

        $report = ActivityReport::create($data);

        return $report->load(['activity', 'submitter']);
    }

    /**
     * Update an existing report.
     */
    public function update(ActivityReport $report, array $data): ActivityReport
    {
        // Convert output_achieved array to JSON if provided
        if (isset($data['output_achieved']) && is_array($data['output_achieved'])) {
            $data['output_achieved'] = json_encode($data['output_achieved']);
        }

        $report->update($data);

        return $report->fresh(['activity', 'submitter']);
    }

    /**
     * Delete a report.
     */
    public function delete(ActivityReport $report): bool
    {
        return $report->delete();
    }

    /**
     * Submit a report for review.
     */
    public function submit(ActivityReport $report): ActivityReport
    {
        $report->update([
            'status' => ActivityReport::STATUS_SUBMITTED,
            'submitted_at' => now(),
        ]);

        return $report->fresh(['activity', 'submitter']);
    }

    /**
     * Approve a report.
     */
    public function approve(ActivityReport $report): ActivityReport
    {
        $report->update(['status' => ActivityReport::STATUS_APPROVED]);

        return $report->fresh(['activity', 'submitter']);
    }

    /**
     * Reject a report.
     */
    public function reject(ActivityReport $report): ActivityReport
    {
        $report->update(['status' => ActivityReport::STATUS_REJECTED]);

        return $report->fresh(['activity', 'submitter']);
    }

    /**
     * Mark a report as reviewed.
     */
    public function markAsReviewed(ActivityReport $report): ActivityReport
    {
        $report->update(['status' => ActivityReport::STATUS_REVIEWED]);

        return $report->fresh(['activity', 'submitter']);
    }

    /**
     * Get reports by activity.
     */
    public function getByActivity(Activity $activity): Collection
    {
        return ActivityReport::forActivity($activity->id)
            ->with(['submitter'])
            ->orderBy('submitted_at', 'desc')
            ->get();
    }

    /**
     * Get pending reports.
     */
    public function getPendingReports(): Collection
    {
        return ActivityReport::whereIn('status', [
            ActivityReport::STATUS_SUBMITTED,
            ActivityReport::STATUS_REVIEWED,
        ])
            ->with(['activity', 'submitter'])
            ->orderBy('submitted_at')
            ->get();
    }

    /**
     * Get approved reports.
     */
    public function getApprovedReports(array $filters = []): Collection|LengthAwarePaginator
    {
        $query = ActivityReport::withStatus(ActivityReport::STATUS_APPROVED)
            ->with(['activity', 'submitter']);

        if (isset($filters['activity_id'])) {
            $query->forActivity($filters['activity_id']);
        }

        $sortBy = $filters['sort_by'] ?? 'submitted_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        if (isset($filters['per_page'])) {
            return $query->paginate($filters['per_page']);
        }

        return $query->get();
    }

    /**
     * Get report statistics.
     */
    public function getStatistics(?int $activityId = null): array
    {
        $query = ActivityReport::query();

        if ($activityId) {
            $query->forActivity($activityId);
        }

        return [
            'total' => $query->count(),
            'draft' => $query->clone()->withStatus(ActivityReport::STATUS_DRAFT)->count(),
            'submitted' => $query->clone()->withStatus(ActivityReport::STATUS_SUBMITTED)->count(),
            'reviewed' => $query->clone()->withStatus(ActivityReport::STATUS_REVIEWED)->count(),
            'approved' => $query->clone()->withStatus(ActivityReport::STATUS_APPROVED)->count(),
            'rejected' => $query->clone()->withStatus(ActivityReport::STATUS_REJECTED)->count(),
            'total_budget_used' => $query->clone()->sum('budget_used'),
            'total_participants' => $query->clone()->sum('participant_count'),
        ];
    }

    /**
     * Get reports by program.
     */
    public function getByProgram(Program $program): Collection
    {
        return ActivityReport::whereHas('activity', function ($query) use ($program) {
            $query->where('program_id', $program->id);
        })
            ->with(['activity', 'submitter'])
            ->orderBy('submitted_at', 'desc')
            ->get();
    }

    /**
     * Get the latest report for an activity.
     */
    public function getLatestReportForActivity(Activity $activity): ?ActivityReport
    {
        return ActivityReport::forActivity($activity->id)
            ->withStatus(ActivityReport::STATUS_APPROVED)
            ->orderBy('submitted_at', 'desc')
            ->first();
    }

    /**
     * Generate report summary for an activity.
     */
    public function generateActivitySummary(Activity $activity): array
    {
        $reports = $this->getByActivity($activity);

        $approvedReports = $reports->where('status', ActivityReport::STATUS_APPROVED);

        return [
            'activity_id' => $activity->id,
            'activity_name' => $activity->name,
            'total_reports' => $reports->count(),
            'approved_reports' => $approvedReports->count(),
            'total_budget_used' => $approvedReports->sum('budget_used'),
            'total_participants' => $approvedReports->sum('participant_count'),
            'latest_report' => $reports->first()?->toArray(),
        ];
    }

    /**
     * Bulk submit reports.
     */
    public function bulkSubmit(array $reportIds): int
    {
        return ActivityReport::whereIn('id', $reportIds)
            ->where('status', ActivityReport::STATUS_DRAFT)
            ->update([
                'status' => ActivityReport::STATUS_SUBMITTED,
                'submitted_at' => now(),
            ]);
    }

    /**
     * Bulk approve reports.
     */
    public function bulkApprove(array $reportIds): int
    {
        return ActivityReport::whereIn('id', $reportIds)
            ->whereIn('status', [
                ActivityReport::STATUS_SUBMITTED,
                ActivityReport::STATUS_REVIEWED,
            ])
            ->update(['status' => ActivityReport::STATUS_APPROVED]);
    }
}
