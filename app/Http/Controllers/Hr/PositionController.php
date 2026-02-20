<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Position;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PositionController extends Controller
{
    /**
     * Display a listing of the positions.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $department = $request->get('department');
        $level = $request->get('level');

        $query = Position::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($department) {
            $query->where('department', $department);
        }

        if ($level) {
            $query->where('level', $level);
        }

        $positions = $query->orderBy('level', 'asc')->orderBy('name', 'asc')->paginate(15);
        $departments = Position::distinct()->pluck('department')->filter()->values();
        $levels = ['1', '2', '3', '4', '5'];

        return view('hr.positions.index', compact('positions', 'search', 'department', 'level', 'departments', 'levels'));
    }

    /**
     * Show the form for creating a new position.
     */
    public function create(): View
    {
        $levels = ['1', '2', '3', '4', '5'];
        
        return view('hr.positions.create', compact('levels'));
    }

    /**
     * Store a newly created position in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:hr_positions,name',
            'description' => 'nullable|string',
            'level' => 'required|integer|min:1|max:5',
            'department' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        Position::create($validated);

        return redirect()->route('hr.positions.index')
            ->with('success', 'Position created successfully.');
    }

    /**
     * Display the specified position.
     */
    public function show(int $id): View
    {
        $position = Position::with('members', 'divisionAssignments')->findOrFail($id);

        return view('hr.positions.show', compact('position'));
    }

    /**
     * Show the form for editing the specified position.
     */
    public function edit(int $id): View
    {
        $position = Position::findOrFail($id);
        $levels = ['1', '2', '3', '4', '5'];

        return view('hr.positions.edit', compact('position', 'levels'));
    }

    /**
     * Update the specified position in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $position = Position::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:hr_positions,name,' . $id,
            'description' => 'nullable|string',
            'level' => 'required|integer|min:1|max:5',
            'department' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $position->update($validated);

        return redirect()->route('hr.positions.show', $id)
            ->with('success', 'Position updated successfully.');
    }

    /**
     * Remove the specified position from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $position = Position::findOrFail($id);

        // Check if position has members
        if ($position->members()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete position with assigned members.');
        }

        $position->delete();

        return redirect()->route('hr.positions.index')
            ->with('success', 'Position deleted successfully.');
    }
}
