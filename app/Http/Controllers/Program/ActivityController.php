<?php

namespace App\Http\Controllers\Program;

use App\Http\Controllers\Controller;
use App\Models\Program\Activity;
use App\Models\Program\Program;
use App\Models\Program\Division;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ActivityController extends Controller
{
    /**
     * Display a listing of the activities.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = Activity::query()->with(['program', 'division', 'schedules', 'participantGroups']);

        if ($request->has('program_id')) {
            $query->forProgram($request->integer('program_id'));
        }

        if ($request->has('division_id')) {
            $query->forDivision($request->integer('division_id'));
        }

        if ($request->has('type')) {
            $query->ofType($request->input('type'));
        }

        if ($request->has('status')) {
            $query->withStatus($request->input('status'));
        }

        if ($request->has('field_id')) {
            $query->whereHas('program', function ($q) use ($request) {
                $q->where('field_id', $request->integer('field_id'));
            });
        }

        $activities = $query->orderBy('start_date', 'desc')->get();

        if ($request->wantsJson() || $request->query('json')) {
            return response()->json([
                'success' => true,
                'data' => $activities,
            ]);
        }

        return view('program.activities.index', compact('activities'));
    }

    /**
     * Show the form for creating a new activity.
     */
    public function create(): View
    {
        $programs = Program::orderBy('name')->get();
        $divisions = Division::orderBy('name')->get();
        $types = Activity::getAvailableTypes();
        $statuses = Activity::getAvailableStatuses();

        return view('program.activities.create', compact('programs', 'divisions', 'types', 'statuses'));
    }

    /**
     * Store a newly created activity.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'program_id' => 'required|exists:programs,id',
            'division_id' => 'required|exists:program_divisions,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:' . implode(',', Activity::getAvailableTypes()),
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:0',
            'registered_count' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:' . implode(',', Activity::getAvailableStatuses()),
        ]);

        $validated['status'] = $validated['status'] ?? Activity::STATUS_DRAFT;

        $activity = Activity::create($validated);

        if ($request->wantsJson() || $request->query('json')) {
            return response()->json([
                'success' => true,
                'message' => 'Kegiatan berhasil dibuat',
                'data' => $activity->load(['program', 'division', 'schedules', 'participantGroups']),
            ], 201);
        }

        return redirect()->route('program.activities.index')
            ->with('success', 'Kegiatan berhasil dibuat');
    }

    /**
     * Display the specified activity.
     */
    public function show(Activity $activity): View|JsonResponse
    {
        $activity->load([
            'program.field',
            'division',
            'schedules',
            'participantGroups',
            'documents',
            'reports',
        ]);

        if (request()->wantsJson() || request()->query('json')) {
            return response()->json([
                'success' => true,
                'data' => $activity,
            ]);
        }

        return view('program.activities.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified activity.
     */
    public function edit(Activity $activity): View
    {
        $programs = Program::orderBy('name')->get();
        $divisions = Division::orderBy('name')->get();
        $types = Activity::getAvailableTypes();
        $statuses = Activity::getAvailableStatuses();

        return view('program.activities.edit', compact('activity', 'programs', 'divisions', 'types', 'statuses'));
    }

    /**
     * Update the specified activity.
     */
    public function update(Request $request, Activity $activity): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'program_id' => 'sometimes|exists:programs,id',
            'division_id' => 'sometimes|exists:program_divisions,id',
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'type' => 'sometimes|string|in:' . implode(',', Activity::getAvailableTypes()),
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:0',
            'registered_count' => 'nullable|integer|min:0',
            'status' => 'nullable|string|in:' . implode(',', Activity::getAvailableStatuses()),
        ]);

        $activity->update($validated);

        if ($request->wantsJson() || $request->query('json')) {
            return response()->json([
                'success' => true,
                'message' => 'Kegiatan berhasil diperbarui',
                'data' => $activity->fresh(['program', 'division', 'schedules', 'participantGroups']),
            ]);
        }

        return redirect()->route('program.activities.show', $activity->id)
            ->with('success', 'Kegiatan berhasil diperbarui');
    }

    /**
     * Remove the specified activity.
     */
    public function destroy(Activity $activity): JsonResponse|RedirectResponse
    {
        // Check if activity has related data
        if ($activity->schedules()->count() > 0 || 
            $activity->documents()->count() > 0 || 
            $activity->reports()->count() > 0) {
            if (request()->wantsJson() || request()->query('json')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus kegiatan yang memiliki data terkait',
                ], 422);
            }

            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus kegiatan yang memiliki data terkait');
        }

        $activity->delete();

        if (request()->wantsJson() || request()->query('json')) {
            return response()->json([
                'success' => true,
                'message' => 'Kegiatan berhasil dihapus',
            ]);
        }

        return redirect()->route('program.activities.index')
            ->with('success', 'Kegiatan berhasil dihapus');
    }

    /**
     * Show calendar view.
     */
    public function calendar(): View
    {
        return view('program.activities.calendar');
    }

    /**
     * Update activity status.
     */
    public function updateStatus(Request $request, Activity $activity): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:' . implode(',', Activity::getAvailableStatuses()),
        ]);

        $activity->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'message' => 'Status kegiatan berhasil diperbarui',
            'data' => $activity->fresh(),
        ]);
    }

    /**
     * Get upcoming activities.
     */
    public function upcoming(Request $request): JsonResponse
    {
        $limit = $request->integer('limit', 10);

        $activities = Activity::with(['program', 'division'])
            ->where('start_date', '>=', now()->toDateString())
            ->whereIn('status', [Activity::STATUS_PUBLISHED, Activity::STATUS_REGISTRATION_OPEN])
            ->orderBy('start_date')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $activities,
        ]);
    }

    /**
     * Get activities by program.
     */
    public function byProgram(Program $program): JsonResponse
    {
        $activities = Activity::forProgram($program->id)
            ->with(['division', 'schedules', 'participantGroups'])
            ->orderBy('start_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $activities,
        ]);
    }

    /**
     * Get activities by division.
     */
    public function byDivision(Division $division): JsonResponse
    {
        $activities = Activity::forDivision($division->id)
            ->with(['program', 'schedules', 'participantGroups'])
            ->orderBy('start_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $activities,
        ]);
    }
}
