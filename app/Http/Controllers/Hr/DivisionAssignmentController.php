<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\DivisionAssignment;
use App\Models\Hr\Member;
use App\Models\Hr\Position;
use App\Models\Program\Division;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DivisionAssignmentController extends Controller
{
    /**
     * Display a listing of the division assignments.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $divisionId = $request->get('division_id');

        $query = DivisionAssignment::with(['member', 'position', 'division']);

        if ($search) {
            $query->whereHas('member', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($divisionId) {
            $query->where('division_id', $divisionId);
        }

        $assignments = $query->orderBy('assigned_date', 'desc')->paginate(15);

        $members = Member::where('status', 'active')->orderBy('name')->get();
        $positions = Position::where('is_active', true)->orderBy('name')->get();
        $divisions = Division::orderBy('name')->get();
        $statuses = ['active', 'completed', 'transferred'];

        return view('hr.assignments.index', compact('assignments', 'search', 'status', 'divisionId', 'members', 'positions', 'divisions', 'statuses'));
    }

    /**
     * Show the form for creating a new assignment.
     */
    public function create(): View
    {
        $members = Member::where('status', 'active')->orderBy('name')->get();
        $positions = Position::where('is_active', true)->orderBy('name')->get();
        $divisions = Division::orderBy('name')->get();

        return view('hr.assignments.create', compact('members', 'positions', 'divisions'));
    }

    /**
     * Store a newly created assignment in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:hr_members,id',
            'position_id' => 'required|exists:hr_positions,id',
            'division_id' => 'required|exists:program_divisions,id',
            'assigned_date' => 'required|date',
            'end_date' => 'nullable|date|after:assigned_date',
            'status' => 'required|in:active,completed,transferred',
            'notes' => 'nullable|string',
        ]);

        // Check if member already has active assignment in this division
        $exists = DivisionAssignment::where('member_id', $validated['member_id'])
            ->where('division_id', $validated['division_id'])
            ->where('status', 'active')
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'This member already has an active assignment in this division.')
                ->withInput();
        }

        DivisionAssignment::create($validated);

        return redirect()->route('hr.assignments.index')
            ->with('success', 'Division assignment created successfully.');
    }

    /**
     * Display the specified assignment.
     */
    public function show(int $id): View
    {
        $assignment = DivisionAssignment::with(['member', 'position', 'division'])->findOrFail($id);

        return view('hr.assignments.show', compact('assignment'));
    }

    /**
     * Show the form for editing the specified assignment.
     */
    public function edit(int $id): View
    {
        $assignment = DivisionAssignment::findOrFail($id);
        $members = Member::where('status', 'active')->orderBy('name')->get();
        $positions = Position::where('is_active', true)->orderBy('name')->get();
        $divisions = Division::orderBy('name')->get();
        $statuses = ['active', 'completed', 'transferred'];

        return view('hr.assignments.edit', compact('assignment', 'members', 'positions', 'divisions', 'statuses'));
    }

    /**
     * Update the specified assignment in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $assignment = DivisionAssignment::findOrFail($id);

        $validated = $request->validate([
            'member_id' => 'required|exists:hr_members,id',
            'position_id' => 'required|exists:hr_positions,id',
            'division_id' => 'required|exists:program_divisions,id',
            'assigned_date' => 'required|date',
            'end_date' => 'nullable|date|after:assigned_date',
            'status' => 'required|in:active,completed,transferred',
            'notes' => 'nullable|string',
        ]);

        $assignment->update($validated);

        return redirect()->route('hr.assignments.show', $id)
            ->with('success', 'Division assignment updated successfully.');
    }

    /**
     * Remove the specified assignment from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $assignment = DivisionAssignment::findOrFail($id);
        $assignment->delete();

        return redirect()->route('hr.assignments.index')
            ->with('success', 'Division assignment deleted successfully.');
    }

    /**
     * Transfer member to another division.
     */
    public function transfer(Request $request, int $id): RedirectResponse
    {
        $assignment = DivisionAssignment::findOrFail($id);

        $validated = $request->validate([
            'new_division_id' => 'required|exists:program_divisions,id',
            'new_position_id' => 'required|exists:hr_positions,id',
            'notes' => 'nullable|string',
        ]);

        // Complete current assignment
        $assignment->update([
            'status' => 'transferred',
            'end_date' => now()->toDateString(),
        ]);

        // Create new assignment
        DivisionAssignment::create([
            'member_id' => $assignment->member_id,
            'position_id' => $validated['new_position_id'],
            'division_id' => $validated['new_division_id'],
            'assigned_date' => now()->toDateString(),
            'status' => 'active',
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('hr.assignments.index')
            ->with('success', 'Member transferred successfully.');
    }
}
