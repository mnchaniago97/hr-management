<?php

namespace App\Http\Controllers\Program;

use App\Http\Controllers\Controller;
use App\Models\Program\Period;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PeriodController extends Controller
{
    /**
     * Display a listing of the periods.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = Period::query()->with(['fields', 'programs']);

        if ($request->has('active_only') && $request->boolean('active_only')) {
            $query->active();
        }

        if ($request->has('year')) {
            $query->where('year_start', '<=', $request->integer('year'))
                  ->where('year_end', '>=', $request->integer('year'));
        }

        $periods = $query->orderBy('year_start', 'desc')->get();

        if ($request->wantsJson() || $request->query('json')) {
            return response()->json([
                'success' => true,
                'data' => $periods,
            ]);
        }

        return view('program.periods.index', compact('periods'));
    }

    /**
     * Show the form for creating a new period.
     */
    public function create(): View
    {
        return view('program.periods.create');
    }

    /**
     * Store a newly created period.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'year_start' => 'required|integer|min:2000|max:2100',
            'year_end' => 'required|integer|min:2000|max:2100|gte:year_start',
            'is_active' => 'boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        // If this period is active, deactivate all other periods
        if ($validated['is_active'] ?? false) {
            Period::where('is_active', true)->update(['is_active' => false]);
        }

        $period = Period::create($validated);

        if ($request->wantsJson() || $request->query('json')) {
            return response()->json([
                'success' => true,
                'message' => 'Periode berhasil dibuat',
                'data' => $period->load(['fields', 'programs']),
            ], 201);
        }

        return redirect()->route('program.periods.index')
            ->with('success', 'Periode berhasil dibuat');
    }

    /**
     * Display the specified period.
     */
    public function show(Period $period): View|JsonResponse
    {
        $period->load(['fields.divisions', 'programs']);

        if (request()->wantsJson() || request()->query('json')) {
            return response()->json([
                'success' => true,
                'data' => $period,
            ]);
        }

        return view('program.periods.show', compact('period'));
    }

    /**
     * Show the form for editing the specified period.
     */
    public function edit(Period $period): View
    {
        return view('program.periods.edit', compact('period'));
    }

    /**
     * Update the specified period.
     */
    public function update(Request $request, Period $period): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'year_start' => 'sometimes|integer|min:2000|max:2100',
            'year_end' => 'sometimes|integer|min:2000|max:2100',
            'is_active' => 'sometimes|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        // If this period is being set to active, deactivate all other periods
        if (isset($validated['is_active']) && $validated['is_active']) {
            Period::where('is_active', true)
                  ->where('id', '!=', $period->id)
                  ->update(['is_active' => false]);
        }

        $period->update($validated);

        if ($request->wantsJson() || $request->query('json')) {
            return response()->json([
                'success' => true,
                'message' => 'Periode berhasil diperbarui',
                'data' => $period->fresh(['fields', 'programs']),
            ]);
        }

        return redirect()->route('program.periods.index')
            ->with('success', 'Periode berhasil diperbarui');
    }

    /**
     * Remove the specified period.
     */
    public function destroy(Period $period): JsonResponse|RedirectResponse
    {
        // Check if period has related data
        if ($period->fields()->count() > 0 || $period->programs()->count() > 0) {
            if (request()->wantsJson() || request()->query('json')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Periode tidak dapat dihapus karena masih memiliki data terkait',
                ], 422);
            }

            return redirect()->back()
                ->with('error', 'Periode tidak dapat dihapus karena masih memiliki data terkait');
        }

        $period->delete();

        if (request()->wantsJson() || request()->query('json')) {
            return response()->json([
                'success' => true,
                'message' => 'Periode berhasil dihapus',
            ]);
        }

        return redirect()->route('program.periods.index')
            ->with('success', 'Periode berhasil dihapus');
    }

    /**
     * Set a period as active.
     */
    public function setActive(Period $period): JsonResponse
    {
        // Deactivate all other periods
        Period::where('is_active', true)->update(['is_active' => false]);

        // Activate the specified period
        $period->update(['is_active' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Periode berhasil diaktifkan',
            'data' => $period->fresh(),
        ]);
    }

    /**
     * Get the currently active period.
     */
    public function active(): JsonResponse
    {
        $period = Period::active()->first();

        if (!$period) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada periode aktif',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $period->load(['fields.divisions', 'programs']),
        ]);
    }
}
