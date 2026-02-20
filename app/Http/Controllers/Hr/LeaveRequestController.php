<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\LeaveRequest;
use App\Models\Hr\Member;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the leave requests.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $type = $request->get('type');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = LeaveRequest::with('member', 'approver');

        if ($search) {
            $query->whereHas('member', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($dateFrom) {
            $query->whereDate('start_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('end_date', '<=', $dateTo);
        }

        $leaveRequests = $query->orderBy('created_at', 'desc')->paginate(15);

        $members = Member::where('status', 'active')->orderBy('name')->get();
        $statuses = ['pending', 'approved', 'rejected'];
        $types = ['annual', 'sick', 'personal', 'maternity', 'paternity', 'unpaid', 'other'];

        return view('hr.leave-requests.index', compact('leaveRequests', 'search', 'status', 'type', 'dateFrom', 'dateTo', 'members', 'statuses', 'types'));
    }

    /**
     * Show the form for creating a new leave request.
     */
    public function create(): View
    {
        $members = Member::where('status', 'active')->orderBy('name')->get();
        $types = ['annual', 'sick', 'personal', 'maternity', 'paternity', 'unpaid', 'other'];

        return view('hr.leave-requests.create', compact('members', 'types'));
    }

    /**
     * Store a newly created leave request in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:hr_members,id',
            'type' => 'required|in:annual,sick,personal,maternity,paternity,unpaid,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        // Check for overlapping leave requests
        $overlap = LeaveRequest::where('member_id', $validated['member_id'])
            ->where('status', '!=', 'rejected')
            ->where(function ($query) use ($validated) {
                $query->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                    ->orWhere(function ($query) use ($validated) {
                        $query->where('start_date', '<=', $validated['start_date'])
                            ->where('end_date', '>=', $validated['end_date']);
                    });
            })
            ->exists();

        if ($overlap) {
            return redirect()->back()
                ->with('error', 'You already have a leave request for this period.')
                ->withInput();
        }

        LeaveRequest::create([
            'member_id' => $validated['member_id'],
            'type' => $validated['type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return redirect()->route('hr.leave-requests.index')
            ->with('success', 'Leave request submitted successfully.');
    }

    /**
     * Display the specified leave request.
     */
    public function show(int $id): View
    {
        $leaveRequest = LeaveRequest::with('member', 'approver')->findOrFail($id);

        return view('hr.leave-requests.show', compact('leaveRequest'));
    }

    /**
     * Approve the specified leave request.
     */
    public function approve(int $id): RedirectResponse
    {
        $leaveRequest = LeaveRequest::findOrFail($id);

        if ($leaveRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'This leave request cannot be approved.');
        }

        $leaveRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        return redirect()->route('hr.leave-requests.show', $id)
            ->with('success', 'Leave request approved successfully.');
    }

    /**
     * Reject the specified leave request.
     */
    public function reject(Request $request, int $id): RedirectResponse
    {
        $leaveRequest = LeaveRequest::findOrFail($id);

        if ($leaveRequest->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'This leave request cannot be rejected.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'notes' => $validated['rejection_reason'],
        ]);

        return redirect()->route('hr.leave-requests.show', $id)
            ->with('success', 'Leave request rejected.');
    }

    /**
     * Remove the specified leave request from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $leaveRequest = LeaveRequest::findOrFail($id);

        if ($leaveRequest->status === 'approved') {
            return redirect()->back()
                ->with('error', 'Cannot delete an approved leave request.');
        }

        $leaveRequest->delete();

        return redirect()->route('hr.leave-requests.index')
            ->with('success', 'Leave request deleted successfully.');
    }

    /**
     * Get pending leave requests count for notifications.
     */
    public function getPendingCount(): int
    {
        return LeaveRequest::where('status', 'pending')->count();
    }
}
