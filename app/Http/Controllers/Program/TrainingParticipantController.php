<?php

namespace App\Http\Controllers\Program;

use App\Http\Controllers\Controller;
use App\Models\Program\TrainingParticipant;
use App\Models\Program\Training;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TrainingParticipantController extends Controller
{
    /**
     * Display a listing of all participants.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $attendanceStatus = $request->get('attendance_status');
        $trainingId = $request->get('training_id');

        $query = TrainingParticipant::with(['training', 'employee']);

        if ($search) {
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($attendanceStatus) {
            $query->where('attendance_status', $attendanceStatus);
        }

        if ($trainingId) {
            $query->where('training_id', $trainingId);
        }

        $participants = $query->orderBy('registration_date', 'desc')->paginate(15);

        $trainings = Training::orderBy('name')->get();
        $attendanceStatuses = ['not_attended', 'attended', 'absent', 'late'];

        return view('program.participants.index', compact('participants', 'search', 'attendanceStatus', 'trainingId', 'trainings', 'attendanceStatuses'));
    }

    /**
     * Display the specified participant.
     */
    public function show(int $id): View
    {
        $participant = TrainingParticipant::with(['training', 'employee'])->findOrFail($id);

        return view('program.participants.show', compact('participant'));
    }

    /**
     * Mark attendance for a participant.
     */
    public function attendance(Request $request, int $id): RedirectResponse
    {
        $participant = TrainingParticipant::findOrFail($id);

        $training = $participant->training;

        if ($training->status !== 'in_progress' && $training->status !== 'completed') {
            return redirect()->back()
                ->with('error', 'Cannot mark attendance for a training that is not in progress or completed.');
        }

        $validated = $request->validate([
            'status' => 'required|in:attended,absent,late',
            'notes' => 'nullable|string',
        ]);

        $participant->update([
            'attendance_status' => $validated['status'],
            'notes' => $validated['notes'] ?? $participant->notes,
        ]);

        return redirect()->route('program.participants.show', $id)
            ->with('success', 'Attendance marked successfully.');
    }

    /**
     * Mark attendance for multiple participants at once.
     */
    public function bulkAttendance(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'training_id' => 'required|exists:program_trainings,id',
            'participants' => 'required|array',
            'participants.*.id' => 'required|exists:program_training_participants,id',
            'participants.*.status' => 'required|in:attended,absent,late',
        ]);

        $training = Training::findOrFail($validated['training_id']);

        if ($training->status !== 'in_progress' && $training->status !== 'completed') {
            return redirect()->back()
                ->with('error', 'Cannot mark attendance for a training that is not in progress or completed.');
        }

        foreach ($validated['participants'] as $participantData) {
            TrainingParticipant::where('id', $participantData['id'])->update([
                'attendance_status' => $participantData['status'],
            ]);
        }

        return redirect()->route('program.trainings.show', $validated['training_id'])
            ->with('success', 'Bulk attendance marked successfully.');
    }

    /**
     * Generate certificate for a participant.
     */
    public function certificate(int $id): View|RedirectResponse
    {
        $participant = TrainingParticipant::with(['training', 'employee'])->findOrFail($id);

        // Check if participant has attended
        if ($participant->attendance_status !== 'attended') {
            return redirect()->back()
                ->with('error', 'Certificate can only be generated for attended participants.');
        }

        // Check if training is completed
        if ($participant->training->status !== 'completed') {
            return redirect()->back()
                ->with('error', 'Certificate can only be generated for completed trainings.');
        }

        return view('program.participants.certificate', compact('participant'));
    }

    /**
     * Issue certificate for a participant.
     */
    public function issueCertificate(Request $request, int $id): RedirectResponse
    {
        $participant = TrainingParticipant::findOrFail($id);

        // Check if participant has attended
        if ($participant->attendance_status !== 'attended') {
            return redirect()->back()
                ->with('error', 'Certificate can only be issued to attended participants.');
        }

        // Check if training is completed
        if ($participant->training->status !== 'completed') {
            return redirect()->back()
                ->with('error', 'Certificate can only be issued for completed trainings.');
        }

        // Generate certificate number
        $certificateNumber = $this->generateCertificateNumber($participant);

        $participant->update([
            'certificate' => $certificateNumber,
        ]);

        return redirect()->route('program.participants.show', $id)
            ->with('success', 'Certificate issued successfully.');
    }

    /**
     * Generate a unique certificate number.
     */
    private function generateCertificateNumber(TrainingParticipant $participant): string
    {
        $year = Carbon::now()->format('Y');
        $trainingId = str_pad($participant->training_id, 4, '0', STR_PAD_LEFT);
        $participantId = str_pad($participant->id, 6, '0', STR_PAD_LEFT);

        return "CERT-{$year}-{$trainingId}-{$participantId}";
    }

    /**
     * Get participants for a specific training.
     */
    public function getByTraining(int $trainingId): View
    {
        $training = Training::findOrFail($trainingId);

        $participants = TrainingParticipant::with('employee')
            ->where('training_id', $trainingId)
            ->orderBy('registration_date', 'desc')
            ->get();

        // Calculate statistics
        $stats = [
            'total' => $participants->count(),
            'attended' => $participants->where('attendance_status', 'attended')->count(),
            'absent' => $participants->where('attendance_status', 'absent')->count(),
            'late' => $participants->where('attendance_status', 'late')->count(),
            'not_attended' => $participants->where('attendance_status', 'not_attended')->count(),
        ];

        return view('program.participants.training', compact('participants', 'training', 'stats'));
    }

    /**
     * Export participants to CSV.
     */
    public function export(int $trainingId): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $training = Training::findOrFail($trainingId);

        $participants = TrainingParticipant::with('employee')
            ->where('training_id', $trainingId)
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"training_participants_{$trainingId}.csv\"",
        ];

        $callback = function () use ($participants) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No', 'Name', 'Email', 'Registration Date', 'Attendance Status', 'Certificate']);

            foreach ($participants as $index => $participant) {
                fputcsv($file, [
                    $index + 1,
                    $participant->employee->name,
                    $participant->employee->email,
                    $participant->registration_date->format('Y-m-d'),
                    $participant->attendance_status,
                    $participant->certificate ?? 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get attendance report for all trainings.
     */
    public function attendanceReport(): View
    {
        $trainings = Training::where('status', 'completed')
            ->orderBy('end_date', 'desc')
            ->get();

        $reportData = [];

        foreach ($trainings as $training) {
            $participants = $training->participants;
            
            $reportData[] = [
                'training' => $training,
                'total' => $participants->count(),
                'attended' => $participants->where('attendance_status', 'attended')->count(),
                'absent' => $participants->where('attendance_status', 'absent')->count(),
                'late' => $participants->where('attendance_status', 'late')->count(),
                'not_attended' => $participants->where('attendance_status', 'not_attended')->count(),
                'certificates_issued' => $participants->whereNotNull('certificate')->count(),
            ];
        }

        return view('program.participants.attendance-report', compact('reportData'));
    }
}
