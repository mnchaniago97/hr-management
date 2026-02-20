<?php

namespace App\Http\Controllers\Program;

use App\Http\Controllers\Controller;
use App\Models\Program\Field;
use App\Models\Program\Period;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FieldController extends Controller
{
    /**
     * Display a listing of the fields.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = Field::query()->with(['period', 'divisions', 'programs']);

        if ($request->has('period_id')) {
            $query->forPeriod($request->integer('period_id'));
        }

        $fields = $query->orderBy('code')->get();

        if ($request->wantsJson() || $request->query('json')) {
            return response()->json([
                'success' => true,
                'data' => $fields,
            ]);
        }

        return view('program.fields.index', compact('fields'));
    }

    /**
     * Show the form for creating a new field.
     */
    public function create(): View
    {
        $periods = Period::orderBy('start_date', 'desc')->get();

        return view('program.fields.create', compact('periods'));
    }

    /**
     * Store a newly created field.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'period_id' => 'required|exists:program_periods,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:program_fields,code',
            'description' => 'nullable|string',
            'head_id' => 'nullable|exists:hr_members,id',
        ]);

        $field = Field::create($validated);

        if ($request->wantsJson() || $request->query('json')) {
            return response()->json([
                'success' => true,
                'message' => 'Bidang berhasil dibuat',
                'data' => $field->load(['period', 'head']),
            ], 201);
        }

        return redirect()->route('program.fields.index')
            ->with('success', 'Bidang berhasil dibuat');
    }

    /**
     * Display the specified field.
     */
    public function show(Field $field): View|JsonResponse
    {
        $field->load(['period', 'divisions', 'programs', 'head']);

        if (request()->wantsJson() || request()->query('json')) {
            return response()->json([
                'success' => true,
                'data' => $field,
            ]);
        }

        return view('program.fields.show', compact('field'));
    }

    /**
     * Show the form for editing the specified field.
     */
    public function edit(Field $field): View
    {
        $periods = Period::orderBy('start_date', 'desc')->get();

        return view('program.fields.edit', compact('field', 'periods'));
    }

    /**
     * Update the specified field.
     */
    public function update(Request $request, Field $field): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'period_id' => 'sometimes|exists:program_periods,id',
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:50|unique:program_fields,code,' . $field->id,
            'description' => 'nullable|string',
            'head_id' => 'nullable|exists:hr_members,id',
        ]);

        $field->update($validated);

        if ($request->wantsJson() || $request->query('json')) {
            return response()->json([
                'success' => true,
                'message' => 'Bidang berhasil diperbarui',
                'data' => $field->fresh(['period', 'divisions', 'programs', 'head']),
            ]);
        }

        return redirect()->route('program.fields.index')
            ->with('success', 'Bidang berhasil diperbarui');
    }

    /**
     * Remove the specified field.
     */
    public function destroy(Field $field): JsonResponse|RedirectResponse
    {
        // Check if field has related data
        if ($field->divisions()->count() > 0 || $field->programs()->count() > 0) {
            if (request()->wantsJson() || request()->query('json')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bidang tidak dapat dihapus karena masih memiliki data terkait',
                ], 422);
            }

            return redirect()->back()
                ->with('error', 'Bidang tidak dapat dihapus karena masih memiliki data terkait');
        }

        $field->delete();

        if (request()->wantsJson() || request()->query('json')) {
            return response()->json([
                'success' => true,
                'message' => 'Bidang berhasil dihapus',
            ]);
        }

        return redirect()->route('program.fields.index')
            ->with('success', 'Bidang berhasil dihapus');
    }

    /**
     * Get fields for the active period.
     */
    public function activePeriod(): JsonResponse
    {
        $period = Period::active()->first();

        if (!$period) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada periode aktif',
            ], 404);
        }

        $fields = Field::forPeriod($period->id)
            ->with(['divisions', 'programs'])
            ->orderBy('code')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $fields,
            'period' => $period,
        ]);
    }

    /**
     * Get available codes for fields.
     */
    public function availableCodes(): JsonResponse
    {
        $usedCodes = Field::pluck('code')->toArray();
        $availableCodes = array_diff(Field::getAvailableCodes(), $usedCodes);

        return response()->json([
            'success' => true,
            'data' => array_values($availableCodes),
        ]);
    }
}
