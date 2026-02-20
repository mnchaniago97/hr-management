<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Models\Hr\Employee;
use App\Models\Hr\Member;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MemberSelfServiceController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $nia = trim((string) $request->get('nia', ''));
        $member = null;
        $error = null;

        if ($nia !== '') {
            if (!$user?->nia) {
                $error = 'Silakan isi NIA akun Anda di profil terlebih dahulu.';
            } elseif ($user->nia !== $nia) {
                $error = 'NIA tidak cocok dengan akun Anda.';
            } else {
                $employee = Employee::with('member')->where('nia', $nia)->first();
                if (!$employee || !$employee->member) {
                    $error = 'Data anggota dengan NIA tersebut tidak ditemukan.';
                } else {
                    $member = $employee->member;
                }
            }
        }

        return view('public.member-self-service', compact('member', 'nia', 'error'));
    }

    public function update(Request $request, int $memberId): RedirectResponse
    {
        $user = $request->user();

        if (!$user?->nia) {
            return redirect()->back()->with('error', 'Silakan isi NIA akun Anda di profil terlebih dahulu.');
        }

        $employee = Employee::where('nia', $user->nia)->first();
        if (!$employee || (int) $employee->member_id !== $memberId) {
            return redirect()->back()->with('error', 'Anda tidak berhak mengubah data anggota ini.');
        }

        $member = Member::findOrFail($memberId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $member->update($validated);

        return redirect()->route('member.self-service', ['nia' => $user->nia])
            ->with('success', 'Data anggota berhasil diperbarui.');
    }
}
