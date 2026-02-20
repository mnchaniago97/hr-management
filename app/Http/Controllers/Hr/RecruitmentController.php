<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Member;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RecruitmentController extends Controller
{
    /**
     * Display a listing of recruitment candidates.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $query = Member::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $members = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('hr.recruitment.index', compact('members', 'search', 'status'));
    }

    /**
     * Show the form for creating a new candidate.
     */
    public function create(): View
    {
        return view('hr.recruitment.create');
    }

    /**
     * Store a newly created candidate in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'status' => 'required|in:active,inactive,on_leave,terminated',
        ]);

        Member::create($validated);

        return redirect()->route('hr.recruitment.index')
            ->with('success', 'Calon anggota berhasil ditambahkan.');
    }

    /**
     * Display the specified candidate.
     */
    public function show(int $id): View
    {
        $member = Member::findOrFail($id);

        return view('hr.recruitment.show', compact('member'));
    }

    /**
     * Show the form for editing the specified candidate.
     */
    public function edit(int $id): View
    {
        $member = Member::findOrFail($id);

        return view('hr.recruitment.edit', compact('member'));
    }

    /**
     * Update the specified candidate in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $member = Member::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'position' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'status' => 'required|in:active,inactive,on_leave,terminated',
        ]);

        $member->update($validated);

        return redirect()->route('hr.recruitment.show', $id)
            ->with('success', 'Data calon anggota berhasil diperbarui.');
    }

    /**
     * Remove the specified candidate from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $member = Member::findOrFail($id);
        $member->delete();

        return redirect()->route('hr.recruitment.index')
            ->with('success', 'Calon anggota berhasil dihapus.');
    }

    /**
     * Display selection process page.
     */
    public function selection(Request $request): View
    {
        $search = $request->get('search');
        
        $query = Member::query();
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $members = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('hr.recruitment.selection', compact('members', 'search'));
    }
}
