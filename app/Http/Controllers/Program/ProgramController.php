<?php

namespace App\Http\Controllers\Program;

use App\Http\Controllers\Controller;
use App\Models\Program\Program;
use App\Models\Program\Period;
use App\Models\Program\Field;
use App\Models\Program\Division;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProgramController extends Controller
{
    /**
     * Display a listing of the programs.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $periodId = $request->get('period_id');
        $fieldId = $request->get('field_id');
        $divisionId = $request->get('division_id');

        $query = Program::with(['period', 'field', 'division']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($periodId) {
            $query->where('period_id', $periodId);
        }

        if ($fieldId) {
            $query->where('field_id', $fieldId);
        }

        if ($divisionId) {
            $query->where('division_id', $divisionId);
        }

        if ($request->query('json')) {
            $programs = $query->orderBy('created_at', 'desc')->get();

            $stats = [
                'total' => Program::count(),
                'active' => Program::where('status', Program::STATUS_ONGOING)->count(),
                'terprogram' => Program::where('type', Program::TYPE_TERPROGRAM)->count(),
                'insidentil' => Program::where('type', Program::TYPE_INSIDENTIL)->count(),
            ];

            return response()->json([
                'stats' => $stats,
                'programs' => $programs,
            ]);
        }

        $programs = $query->orderBy('created_at', 'desc')->paginate(15);

        $periods = Period::orderBy('start_date', 'desc')->get();
        $fields = Field::orderBy('name')->get();
        $divisions = Division::orderBy('name')->get();
        $statuses = Program::getAvailableStatuses();

        return view('program.programs.index', compact('programs', 'search', 'status', 'periodId', 'fieldId', 'divisionId', 'periods', 'fields', 'divisions', 'statuses'));
    }

    /**
     * Show the form for creating a new program.
     */
    public function create(): View
    {
        $periods = Period::orderBy('start_date', 'desc')->get();
        $fields = Field::orderBy('name')->get();
        $divisions = Division::orderBy('name')->get();
        $statuses = Program::getAvailableStatuses();

        return view('program.programs.create', compact('periods', 'fields', 'divisions', 'statuses'));
    }

    /**
     * Store a newly created program in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'period_id' => 'required|exists:program_periods,id',
            'field_id' => 'required|exists:program_fields,id',
            'division_id' => 'required|exists:program_divisions,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:terprogram,insidentil',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:' . implode(',', Program::getAvailableStatuses()),
            'budget' => 'nullable|numeric|min:0',
        ]);

        Program::create($validated);

        return redirect()->route('program.programs.index')
            ->with('success', 'Program created successfully.');
    }

    /**
     * Display the specified program.
     */
    public function show(int $id): View
    {
        $program = Program::with(['period', 'field', 'division', 'activities'])->findOrFail($id);

        return view('program.programs.show', compact('program'));
    }

    /**
     * Show the form for editing the specified program.
     */
    public function edit(int $id): View
    {
        $program = Program::findOrFail($id);
        $periods = Period::orderBy('start_date', 'desc')->get();
        $fields = Field::orderBy('name')->get();
        $divisions = Division::orderBy('name')->get();
        $statuses = Program::getAvailableStatuses();

        return view('program.programs.edit', compact('program', 'periods', 'fields', 'divisions', 'statuses'));
    }

    /**
     * Update the specified program in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $program = Program::findOrFail($id);

        $validated = $request->validate([
            'period_id' => 'required|exists:program_periods,id',
            'field_id' => 'required|exists:program_fields,id',
            'division_id' => 'required|exists:program_divisions,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:terprogram,insidentil',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:' . implode(',', Program::getAvailableStatuses()),
            'budget' => 'nullable|numeric|min:0',
        ]);

        $program->update($validated);

        return redirect()->route('program.programs.show', $id)
            ->with('success', 'Program updated successfully.');
    }

    /**
     * Remove the specified program from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $program = Program::findOrFail($id);

        // Check if program has activities
        if ($program->activities()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete program with activities.');
        }

        $program->delete();

        return redirect()->route('program.programs.index')
            ->with('success', 'Program deleted successfully.');
    }

    /**
     * Get programs by period.
     */
    public function getByPeriod(int $periodId): View
    {
        $period = Period::findOrFail($periodId);
        $programs = Program::with(['field', 'division'])
            ->where('period_id', $periodId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('program.programs.period', compact('programs', 'period'));
    }

    /**
     * Get programs by field.
     */
    public function getByField(int $fieldId): View
    {
        $field = Field::findOrFail($fieldId);
        $programs = Program::with(['period', 'division'])
            ->where('field_id', $fieldId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('program.programs.field', compact('programs', 'field'));
    }

    /**
     * Get programs by division.
     */
    public function getByDivision(int $divisionId): View
    {
        $division = Division::findOrFail($divisionId);
        $programs = Program::with(['period', 'field'])
            ->where('division_id', $divisionId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('program.programs.division', compact('programs', 'division'));
    }
}
