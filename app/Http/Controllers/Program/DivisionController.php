<?php

namespace App\Http\Controllers\Program;

use App\Http\Controllers\Controller;
use App\Models\Program\Division;
use App\Models\Program\Field;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DivisionController extends Controller
{
    /**
     * Display a listing of the divisions.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = Division::query()->with(['field', 'activities']);

        if ($request->has('field_id')) {
            $query->forField($request->integer('field_id'));
        }

        $divisions = $query->orderBy('name')->get();

        if ($request->wantsJson() || $request->query('json')) {
            return response()->json([
                'success' => true,
                'data' => $divisions,
            ]);
        }

        return view('program.divisions.index', compact('divisions'));
    }

    /**
     * Show the form for creating a new division.
     */
    public function create(): View
    {
        $fields = Field::orderBy('name')->get();

        return view('program.divisions.create', compact('fields'));
    }

    /**
     * Store a newly created division.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'field_id' => 'required|exists:program_fields,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:program_divisions,code',
            'description' => 'nullable|string',
            'head_id' => 'nullable|exists:hr_members,id',
        ]);

        $division = Division::create($validated);

        if ($request->wantsJson() || $request->query('json')) {
            return response()->json([
                'success' => true,
                'message' => 'Divisi berhasil dibuat',
                'data' => $division->load(['field', 'head']),
            ], 201);
        }

        return redirect()->route('program.divisions.index')
            ->with('success', 'Divisi berhasil dibuat');
    }

    /**
     * Display the specified division.
     */
    public function show(Division $division): View|JsonResponse
    {
        $division->load(['field', 'activities', 'head']);

        if (request()->wantsJson() || request()->query('json')) {
            return response()->json([
                'success' => true,
                'data' => $division,
            ]);
        }

        return view('program.divisions.show', compact('division'));
    }

    /**
     * Show the form for editing the specified division.
     */
    public function edit(Division $division): View
    {
        $fields = Field::orderBy('name')->get();

        return view('program.divisions.edit', compact('division', 'fields'));
    }

    /**
     * Update the specified division.
     */
    public function update(Request $request, Division $division): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'field_id' => 'sometimes|exists:program_fields,id',
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:50|unique:program_divisions,code,' . $division->id,
            'description' => 'nullable|string',
            'head_id' => 'nullable|exists:hr_members,id',
        ]);

        $division->update($validated);

        if ($request->wantsJson() || $request->query('json')) {
            return response()->json([
                'success' => true,
                'message' => 'Divisi berhasil diperbarui',
                'data' => $division->fresh(['field', 'activities', 'head']),
            ]);
        }

        return redirect()->route('program.divisions.index')
            ->with('success', 'Divisi berhasil diperbarui');
    }

    /**
     * Remove the specified division.
     */
    public function destroy(Division $division): JsonResponse|RedirectResponse
    {
        // Check if division has related activities
        if ($division->activities()->count() > 0) {
            if (request()->wantsJson() || request()->query('json')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Divisi tidak dapat dihapus karena masih memiliki kegiatan',
                ], 422);
            }

            return redirect()->back()
                ->with('error', 'Divisi tidak dapat dihapus karena masih memiliki kegiatan');
        }

        $division->delete();

        if (request()->wantsJson() || request()->query('json')) {
            return response()->json([
                'success' => true,
                'message' => 'Divisi berhasil dihapus',
            ]);
        }

        return redirect()->route('program.divisions.index')
            ->with('success', 'Divisi berhasil dihapus');
    }

    /**
     * Get divisions by field.
     */
    public function byField(Field $field): JsonResponse
    {
        $divisions = Division::forField($field->id)
            ->with(['activities'])
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $divisions,
        ]);
    }
}
