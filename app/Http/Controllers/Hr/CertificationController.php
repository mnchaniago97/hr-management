<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Certification;
use App\Models\Hr\Member;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CertificationController extends Controller
{
    /**
     * Display a listing of certifications.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $query = Certification::with('member');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('provider', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $certifications = $query->orderBy('issue_date', 'desc')->paginate(15);

        return view('hr.certifications.index', compact('certifications', 'search', 'status'));
    }

    /**
     * Show the form for creating a new certification.
     */
    public function create(): View
    {
        $members = Member::where('status', 'active')->orderBy('name')->get();

        return view('hr.certifications.create', compact('members'));
    }

    /**
     * Store a newly created certification in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:hr_members,id',
            'name' => 'required|string|max:255',
            'provider' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'credential_id' => 'nullable|string|max:255',
            'credential_url' => 'nullable|url',
            'status' => 'required|in:active,expired,revoked',
        ]);

        Certification::create($validated);

        return redirect()->route('hr.certifications.index')
            ->with('success', 'Sertifikasi berhasil ditambahkan.');
    }

    /**
     * Display the specified certification.
     */
    public function show(int $id): View
    {
        $certification = Certification::with('member')->findOrFail($id);

        return view('hr.certifications.show', compact('certification'));
    }

    /**
     * Show the form for editing the specified certification.
     */
    public function edit(int $id): View
    {
        $certification = Certification::findOrFail($id);
        $members = Member::where('status', 'active')->orderBy('name')->get();

        return view('hr.certifications.edit', compact('certification', 'members'));
    }

    /**
     * Update the specified certification in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $certification = Certification::findOrFail($id);

        $validated = $request->validate([
            'member_id' => 'required|exists:hr_members,id',
            'name' => 'required|string|max:255',
            'provider' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:issue_date',
            'credential_id' => 'nullable|string|max:255',
            'credential_url' => 'nullable|url',
            'status' => 'required|in:active,expired,revoked',
        ]);

        $certification->update($validated);

        return redirect()->route('hr.certifications.show', $id)
            ->with('success', 'Sertifikasi berhasil diperbarui.');
    }

    /**
     * Remove the specified certification from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $certification = Certification::findOrFail($id);
        $certification->delete();

        return redirect()->route('hr.certifications.index')
            ->with('success', 'Sertifikasi berhasil dihapus.');
    }
}
