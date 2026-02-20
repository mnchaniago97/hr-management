<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Imports\MembersImport;
use App\Models\Hr\Member;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;

class MemberController extends Controller
{
    /**
     * Display a listing of the members.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $memberType = $request->get('member_type');
        $department = $request->get('department');

        $query = Member::with('profile');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhereHas('profile', function ($q) use ($search) {
                      $q->where('nia', 'like', "%{$search}%");
                  });
            });
        }

        if ($status) {
            $query->where('hr_members.status', $status);
        }

        if ($memberType) {
            $query->where('hr_members.member_type', $memberType);
        }

        if ($department) {
            $query->where('hr_members.department', $department);
        }

        if ($request->query('json')) {
            return response()->json(
                $query->orderBy('created_at', 'desc')->get()
            );
        }

        $members = $query
            ->leftJoin('hr_employees', 'hr_employees.member_id', '=', 'hr_members.id')
            ->select('hr_members.*')
            ->orderBy('hr_employees.nia')
            ->orderBy('hr_members.name')
            ->paginate(100);
        $departments = Member::distinct()->pluck('department')->filter()->values();

        return view('hr.members.index', compact('members', 'search', 'status', 'memberType', 'department', 'departments'));
    }

    /**
     * Show the form for creating a new member.
     */
    public function create(): View
    {
        return view('hr.members.create');
    }

    /**
     * Store a newly created member in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'status' => $request->input('status') ?: null,
        ]);

        $validated = $request->validate([
            'nia' => 'required|string|max:50|unique:hr_employees,nia',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive,on_leave,terminated',
            'member_type' => 'nullable|in:AM,AT,Alumni',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = time() . '_' . $photo->getClientOriginalName();
            $photo->storeAs('members', $filename, 'public');
            $validated['photo'] = $filename;
        }

        $nia = $validated['nia'];
        unset($validated['nia']);

        if (!isset($validated['member_type'])) {
            $validated['member_type'] = 'AM';
        }

        $member = Member::create($validated);

        \App\Models\Hr\Employee::updateOrCreate(
            ['member_id' => $member->id],
            ['nia' => $nia]
        );

        return redirect()->route('hr.members.index')
            ->with('success', 'Member created successfully.');
    }

    /**
     * Handle bulk member import.
     */
    public function import(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:xls,xlsx,csv|max:5120',
        ]);

        $import = new MembersImport();
        Excel::import($import, $validated['file']);

        $failures = $import->failures();
        if ($failures->isNotEmpty()) {
            $messages = $failures->map(function ($failure) {
                return 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            })->values();

            return redirect()->back()
                ->with('error', 'Sebagian data gagal diimport.')
                ->with('importFailures', $messages);
        }

        return redirect()->back()->with('success', 'Import anggota berhasil.');
    }

    /**
     * Display the specified member.
     */
    public function show(int|string $id): View
    {
        $member = Member::with(['profile', 'attendances', 'leaveRequests', 'itemLoans', 'trainingParticipants'])->findOrFail((int) $id);

        return view('hr.members.show', compact('member'));
    }

    /**
     * Show the form for editing the specified member.
     */
    public function edit(int|string $id): View
    {
        $member = Member::findOrFail((int) $id);

        return view('hr.members.edit', compact('member'));
    }

    /**
     * Update the specified member in storage.
     */
    public function update(Request $request, int|string $id): RedirectResponse
    {
        $member = Member::findOrFail((int) $id);

        $request->merge([
            'status' => $request->input('status') ?: null,
        ]);

        $validated = $request->validate([
            'nia' => 'required|string|max:50|unique:hr_employees,nia,' . (int) $id . ',member_id',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'status' => 'nullable|in:active,inactive,on_leave,terminated',
            'member_type' => 'nullable|in:AM,AT,Alumni',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($member->photo) {
                \Storage::disk('public')->delete('members/' . $member->photo);
            }
            $photo = $request->file('photo');
            $filename = time() . '_' . $photo->getClientOriginalName();
            $photo->storeAs('members', $filename, 'public');
            $validated['photo'] = $filename;
        }

        $nia = $validated['nia'];
        unset($validated['nia']);

        if (!isset($validated['member_type'])) {
            $validated['member_type'] = 'AM';
        }

        $member->update($validated);

        \App\Models\Hr\Employee::updateOrCreate(
            ['member_id' => $member->id],
            ['nia' => $nia]
        );

        return redirect()->route('hr.members.show', (int) $id)
            ->with('success', 'Member updated successfully.');
    }

    /**
     * Remove the specified member from storage.
     */
    public function destroy(int|string $id): RedirectResponse
    {
        $member = Member::findOrFail((int) $id);

        // Delete photo if exists
        if ($member->photo) {
            \Storage::disk('public')->delete('members/' . $member->photo);
        }

        $member->delete();

        return redirect()->route('hr.members.index')
            ->with('success', 'Member deleted successfully.');
    }
}
