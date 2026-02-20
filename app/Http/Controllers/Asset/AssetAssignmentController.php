<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Models\Asset\AssetAssignment;
use App\Models\Asset\AssetItem;
use App\Models\Hr\Member;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class AssetAssignmentController extends Controller
{
    /**
     * Display a listing of the asset assignments.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = AssetAssignment::with(['item', 'member']);

        if ($search) {
            $query->whereHas('member', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('item', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($dateFrom) {
            $query->whereDate('assignment_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('assignment_date', '<=', $dateTo);
        }

        $assignments = $query->orderBy('assignment_date', 'desc')->paginate(15);

        $members = Member::where('status', 'active')->orderBy('name')->get();
        $statuses = ['borrowed', 'returned', 'overdue'];

        return view('asset.loans.index', compact('assignments', 'search', 'status', 'dateFrom', 'dateTo', 'members', 'statuses'));
    }

    /**
     * Show the form for creating a new assignment.
     */
    public function create(): View
    {
        $members = Member::where('status', 'active')->orderBy('name')->get();
        $availableItems = AssetItem::where('status', 'available')
            ->where('condition', '!=', 'poor')
            ->orderBy('name')
            ->get();

        return view('asset.loans.create', compact('members', 'availableItems'));
    }

    /**
     * Public asset loan form.
     */
    public function publicForm(): View
    {
        $members = Member::where('status', 'active')->orderBy('name')->get();
        $availableItems = AssetItem::where('status', 'available')
            ->where('condition', '!=', 'poor')
            ->orderBy('name')
            ->get();

        return view('public.asset-loan', [
            'title' => 'Peminjaman Aset',
            'members' => $members,
            'availableItems' => $availableItems,
        ]);
    }

    /**
     * Store a newly created assignment in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:asset_items,id',
            'member_id' => 'required|exists:hr_members,id',
            'assignment_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:assignment_date',
            'notes' => 'nullable|string',
        ]);

        // Check if item is available
        $item = AssetItem::findOrFail($validated['item_id']);
        if ($item->status !== 'available') {
            return redirect()->back()
                ->with('error', 'This item is not available for assignment.')
                ->withInput();
        }

        // Check if member already has this item assigned
        $existingAssignment = AssetAssignment::where('item_id', $validated['item_id'])
            ->where('member_id', $validated['member_id'])
            ->where('status', 'borrowed')
            ->exists();

        if ($existingAssignment) {
            return redirect()->back()
                ->with('error', 'This member already has this item assigned.')
                ->withInput();
        }

        // Create the assignment
        $assignment = AssetAssignment::create([
            'item_id' => $validated['item_id'],
            'member_id' => $validated['member_id'],
            'assignment_date' => $validated['assignment_date'],
            'return_date' => $validated['return_date'],
            'status' => 'borrowed',
            'notes' => $validated['notes'],
        ]);

        // Update item status to borrowed
        $item->update(['status' => 'borrowed']);

        return redirect()->route('asset.assignments.show', $assignment->id)
            ->with('success', 'Asset assignment created successfully.');
    }

    /**
     * Store a public asset loan request.
     */
    public function publicStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:asset_items,id',
            'member_id' => 'required|exists:hr_members,id',
            'assignment_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:assignment_date',
            'notes' => 'nullable|string',
        ]);

        $item = AssetItem::findOrFail($validated['item_id']);
        if ($item->status !== 'available') {
            return redirect()->back()
                ->with('error', 'Aset ini sedang tidak tersedia.')
                ->withInput();
        }

        $existingAssignment = AssetAssignment::where('item_id', $validated['item_id'])
            ->where('member_id', $validated['member_id'])
            ->where('status', 'borrowed')
            ->exists();

        if ($existingAssignment) {
            return redirect()->back()
                ->with('error', 'Aset ini masih dipinjam oleh anggota yang sama.')
                ->withInput();
        }

        AssetAssignment::create([
            'item_id' => $validated['item_id'],
            'member_id' => $validated['member_id'],
            'assignment_date' => $validated['assignment_date'],
            'return_date' => $validated['return_date'],
            'status' => 'borrowed',
            'notes' => $validated['notes'],
        ]);

        $item->update(['status' => 'borrowed']);

        return redirect()->back()
            ->with('success', 'Peminjaman aset berhasil dikirim.');
    }

    /**
     * Display the specified assignment.
     */
    public function show(int $id): View
    {
        $assignment = AssetAssignment::with(['item', 'member'])->findOrFail($id);

        return view('asset.loans.show', compact('assignment'));
    }

    /**
     * Mark the item as returned.
     */
    public function return(Request $request, int $id): RedirectResponse
    {
        $assignment = AssetAssignment::findOrFail($id);

        if ($assignment->status !== 'borrowed') {
            return redirect()->back()
                ->with('error', 'This assignment is not active.');
        }

        $validated = $request->validate([
            'condition_notes' => 'nullable|string',
        ]);

        // Update assignment with return date
        $assignment->update([
            'actual_return_date' => Carbon::now(),
            'status' => 'returned',
            'notes' => $assignment->notes . "\n" . ($validated['condition_notes'] ?? ''),
        ]);

        // Update item status back to available
        $assignment->item->update(['status' => 'available']);

        return redirect()->route('asset.assignments.show', $id)
            ->with('success', 'Item returned successfully.');
    }

    /**
     * Remove the specified assignment from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $assignment = AssetAssignment::findOrFail($id);

        if ($assignment->status === 'borrowed') {
            // Update item status back to available
            $assignment->item->update(['status' => 'available']);
        }

        $assignment->delete();

        return redirect()->route('asset.assignments.index')
            ->with('success', 'Assignment record deleted successfully.');
    }

    /**
     * Get overdue assignments.
     */
    public function getOverdue(): View
    {
        $today = Carbon::now()->toDateString();
        
        $assignments = AssetAssignment::with(['item', 'member'])
            ->where('status', 'borrowed')
            ->whereDate('return_date', '<', $today)
            ->orderBy('return_date', 'asc')
            ->paginate(15);

        return view('asset.loans.overdue', compact('assignments'));
    }

    /**
     * Get assignments for a specific member.
     */
    public function getByMember(int $memberId): View
    {
        $member = Member::findOrFail($memberId);
        
        $assignments = AssetAssignment::with('item')
            ->where('member_id', $memberId)
            ->orderBy('assignment_date', 'desc')
            ->paginate(15);

        return view('asset.loans.member', compact('assignments', 'member'));
    }

    /**
     * Extend assignment period.
     */
    public function extend(Request $request, int $id): RedirectResponse
    {
        $assignment = AssetAssignment::findOrFail($id);

        if ($assignment->status !== 'borrowed') {
            return redirect()->back()
                ->with('error', 'Cannot extend a returned assignment.');
        }

        $validated = $request->validate([
            'new_return_date' => 'required|date|after:return_date',
        ]);

        $assignment->update([
            'return_date' => $validated['new_return_date'],
        ]);

        return redirect()->route('asset.assignments.show', $id)
            ->with('success', 'Assignment period extended successfully.');
    }
}
