<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Program\Training;
use App\Models\Hr\Member;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TrainingController extends Controller
{
    /**
     * Display a listing of trainings.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $query = Training::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $trainings = $query->orderBy('start_date', 'desc')->paginate(15);

        return view('hr.trainings.index', compact('trainings', 'search', 'status'));
    }

    /**
     * Show the form for creating a new training.
     */
    public function create(): View
    {
        return view('hr.trainings.create');
    }

    /**
     * Store a newly created training in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'status' => 'required|in:planned,ongoing,completed,cancelled',
        ]);

        Training::create($validated);

        return redirect()->route('hr.trainings.index')
            ->with('success', 'Pelatihan berhasil ditambahkan.');
    }

    /**
     * Display the specified training.
     */
    public function show(int $id): View
    {
        $training = Training::with('participants')->findOrFail($id);

        return view('hr.trainings.show', compact('training'));
    }

    /**
     * Show the form for editing the specified training.
     */
    public function edit(int $id): View
    {
        $training = Training::findOrFail($id);

        return view('hr.trainings.edit', compact('training'));
    }

    /**
     * Update the specified training in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $training = Training::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1',
            'status' => 'required|in:planned,ongoing,completed,cancelled',
        ]);

        $training->update($validated);

        return redirect()->route('hr.trainings.show', $id)
            ->with('success', 'Pelatihan berhasil diperbarui.');
    }

    /**
     * Remove the specified training from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $training = Training::findOrFail($id);
        $training->delete();

        return redirect()->route('hr.trainings.index')
            ->with('success', 'Pelatihan berhasil dihapus.');
    }
}
