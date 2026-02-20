<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Attendance;
use App\Models\Hr\Member;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the attendances.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = Attendance::with('member');

        if ($search) {
            $query->whereHas('member', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($dateFrom) {
            $query->whereDate('date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('date', '<=', $dateTo);
        }

        $attendances = $query->orderBy('date', 'desc')->orderBy('check_in', 'desc')->paginate(15);
        
        $members = Member::where('status', 'active')->orderBy('name')->get();
        $statuses = ['present', 'absent', 'late', 'on_leave'];

        return view('hr.attendances.index', compact('attendances', 'search', 'status', 'dateFrom', 'dateTo', 'members', 'statuses'));
    }

    /**
     * Show the form for creating a new attendance.
     */
    public function create(): View
    {
        $members = Member::where('status', 'active')->orderBy('name')->get();
        
        return view('hr.attendances.create', compact('members'));
    }

    /**
     * Public attendance form for members.
     */
    public function publicForm(): View
    {
        $members = Member::where('status', 'active')->orderBy('name')->get();

        return view('public.attendance', [
            'title' => 'Absensi Anggota',
            'members' => $members,
        ]);
    }

    /**
     * Store a newly created attendance in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:hr_members,id',
            'date' => 'required|date',
            'check_in' => 'nullable',
            'check_out' => 'nullable|after:check_in',
            'status' => 'required|in:present,absent,late,on_leave',
            'notes' => 'nullable|string',
        ]);

        // Check if attendance already exists for this member on this date
        $exists = Attendance::where('member_id', $validated['member_id'])
            ->whereDate('date', $validated['date'])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Attendance already recorded for this member on this date.')
                ->withInput();
        }

        Attendance::create($validated);

        return redirect()->route('hr.attendances.index')
            ->with('success', 'Attendance recorded successfully.');
    }

    /**
     * Display the specified attendance.
     */
    public function show(int|string $id): View
    {
        $attendance = Attendance::with('member')->findOrFail((int) $id);

        return view('hr.attendances.show', compact('attendance'));
    }

    /**
     * Remove the specified attendance from storage.
     */
    public function destroy(int|string $id): RedirectResponse
    {
        $attendance = Attendance::findOrFail((int) $id);
        $attendance->delete();

        return redirect()->route('hr.attendances.index')
            ->with('success', 'Attendance deleted successfully.');
    }

    /**
     * Generate attendance report.
     */
    public function report(Request $request): View
    {
        $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', Carbon::now()->endOfMonth()->toDateString());
        $memberId = $request->get('member_id');

        $query = Attendance::with('member')
            ->whereBetween('date', [$dateFrom, $dateTo]);

        if ($memberId) {
            $query->where('member_id', $memberId);
        }

        $attendances = $query->orderBy('date', 'desc')->get();
        
        // Calculate statistics
        $stats = [
            'total' => $attendances->count(),
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'on_leave' => $attendances->where('status', 'on_leave')->count(),
        ];

        $members = Member::where('status', 'active')->orderBy('name')->get();

        return view('hr.attendances.report', compact('attendances', 'stats', 'dateFrom', 'dateTo', 'memberId', 'members'));
    }

    /**
     * Quick check-in for current user.
     */
    public function quickCheckIn(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:hr_members,id',
        ]);

        $today = Carbon::now()->toDateString();
        $now = Carbon::now();

        // Check if already checked in today
        $exists = Attendance::where('member_id', $validated['member_id'])
            ->whereDate('date', $today)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Anda sudah check-in hari ini.');
        }

        // Determine status based on time (late after 9 AM)
        $status = $now->format('H:i') > '09:00' ? 'late' : 'present';

        Attendance::create([
            'member_id' => $validated['member_id'],
            'date' => $today,
            'check_in' => $now,
            'status' => $status,
        ]);

        return redirect()->back()
            ->with('success', 'Check-in berhasil dicatat.');
    }

    /**
     * Quick check-out for current user.
     */
    public function quickCheckOut(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:hr_members,id',
        ]);

        $today = Carbon::now()->toDateString();
        $now = Carbon::now();

        $attendance = Attendance::where('member_id', $validated['member_id'])
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            return redirect()->back()
                ->with('error', 'Belum ada check-in untuk hari ini.');
        }

        if ($attendance->check_out) {
            return redirect()->back()
                ->with('error', 'Anda sudah check-out hari ini.');
        }

        $attendance->update([
            'check_out' => $now,
        ]);

        return redirect()->back()
            ->with('success', 'Check-out berhasil dicatat.');
    }
}
