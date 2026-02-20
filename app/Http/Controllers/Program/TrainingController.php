<?php

namespace App\Http\Controllers\Program;

use App\Http\Controllers\Controller;
use App\Models\Program\Training;
use App\Models\Program\TrainingParticipant;
use App\Models\Hr\Employee;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class TrainingController extends Controller
{
    /**
     * Display a listing of the trainings.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = Training::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($dateFrom) {
            $query->whereDate('start_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('end_date', '<=', $dateTo);
        }

        $trainings = $query->orderBy('start_date', 'desc')->paginate(15);

        $statuses = ['draft', 'open', 'in_progress', 'completed', 'cancelled'];

        return view('program.trainings.index', compact('trainings', 'search', 'status', 'dateFrom', 'dateTo', 'statuses'));
    }

    /**
     * Show the form for creating a new training.
     */
    public function create(): View
    {
        $statuses = ['draft', 'open', 'in_progress', 'completed', 'cancelled'];

        return view('program.trainings.create', compact('statuses'));
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
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:draft,open,in_progress,completed,cancelled',
        ]);

        $validated['registered_count'] = 0;

        Training::create($validated);

        return redirect()->route('program.trainings.index')
            ->with('success', 'Training created successfully.');
    }

    /**
     * Display the specified training.
     */
    public function show(int $id): View
    {
        $training = Training::with('participants.employee')->findOrFail($id);

        $availableSlots = $training->capacity - $training->registered_count;
        $isFull = $availableSlots <= 0;

        return view('program.trainings.show', compact('training', 'availableSlots', 'isFull'));
    }

    /**
     * Show the form for editing the specified training.
     */
    public function edit(int $id): View
    {
        $training = Training::findOrFail($id);
        $statuses = ['draft', 'open', 'in_progress', 'completed', 'cancelled'];

        return view('program.trainings.edit', compact('training', 'statuses'));
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
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'status' => 'required|in:draft,open,in_progress,completed,cancelled',
        ]);

        // Check if reducing capacity below registered count
        if ($validated['capacity'] < $training->registered_count) {
            return redirect()->back()
                ->with('error', 'Capacity cannot be less than the number of registered participants.')
                ->withInput();
        }

        $training->update($validated);

        return redirect()->route('program.trainings.show', $id)
            ->with('success', 'Training updated successfully.');
    }

    /**
     * Remove the specified training from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $training = Training::findOrFail($id);

        // Check if there are registered participants
        if ($training->registered_count > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete training with registered participants.');
        }

        $training->delete();

        return redirect()->route('program.trainings.index')
            ->with('success', 'Training deleted successfully.');
    }

    /**
     * Register a participant for the training.
     */
    public function register(Request $request, int $id): RedirectResponse
    {
        $training = Training::findOrFail($id);

        if ($training->status === 'cancelled' || $training->status === 'completed') {
            return redirect()->back()
                ->with('error', 'Cannot register for a cancelled or completed training.');
        }

        if ($training->status === 'draft') {
            return redirect()->back()
                ->with('error', 'Cannot register for a draft training.');
        }

        // Check capacity
        if ($training->registered_count >= $training->capacity) {
            return redirect()->back()
                ->with('error', 'Training is full.');
        }

        $validated = $request->validate([
            'employee_id' => 'required|exists:hr_employees,id',
        ]);

        // Check if employee is already registered
        $exists = TrainingParticipant::where('training_id', $id)
            ->where('employee_id', $validated['employee_id'])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Employee is already registered for this training.');
        }

        // Create participant
        TrainingParticipant::create([
            'training_id' => $id,
            'employee_id' => $validated['employee_id'],
            'registration_date' => Carbon::now(),
            'attendance_status' => 'not_attended',
        ]);

        // Update registered count
        $training->increment('registered_count');

        return redirect()->route('program.trainings.show', $id)
            ->with('success', 'Participant registered successfully.');
    }

    /**
     * Cancel registration for a participant.
     */
    public function cancelRegistration(int $trainingId, int $participantId): RedirectResponse
    {
        $training = Training::findOrFail($trainingId);
        $participant = TrainingParticipant::findOrFail($participantId);

        if ($participant->attendance_status === 'attended') {
            return redirect()->back()
                ->with('error', 'Cannot cancel registration for attended participant.');
        }

        $participant->delete();
        $training->decrement('registered_count');

        return redirect()->route('program.trainings.show', $trainingId)
            ->with('success', 'Registration cancelled successfully.');
    }

    /**
     * Get upcoming trainings.
     */
    public function getUpcoming(): View
    {
        $today = Carbon::now()->toDateString();

        $trainings = Training::where('status', 'open')
            ->whereDate('start_date', '>=', $today)
            ->whereColumn('registered_count', '<', 'capacity')
            ->orderBy('start_date', 'asc')
            ->paginate(15);

        return view('program.trainings.upcoming', compact('trainings'));
    }

    /**
     * Get trainings for a specific employee.
     */
    public function getByEmployee(int $employeeId): View
    {
        $employee = Employee::findOrFail($employeeId);

        $trainings = TrainingParticipant::with('training')
            ->where('employee_id', $employeeId)
            ->orderBy('registration_date', 'desc')
            ->paginate(15);

        return view('program.trainings.employee', compact('trainings', 'employee'));
    }
}
