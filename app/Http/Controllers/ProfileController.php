<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        return view('pages.profile', ['title' => 'Profile']);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nia' => 'nullable|string|max:50|unique:users,nia,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'country' => 'nullable|string|max:100',
            'city_state' => 'nullable|string|max:150',
            'postal_code' => 'nullable|string|max:20',
            'tax_id' => 'nullable|string|max:50',
        ]);

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $avatar->getClientOriginalExtension();
            if (!is_dir(public_path('uploads/avatars'))) {
                @mkdir(public_path('uploads/avatars'), 0755, true);
            }
            $avatar->move(public_path('uploads/avatars'), $filename);

            if ($user->avatar && file_exists(public_path('uploads/avatars/' . $user->avatar))) {
                @unlink(public_path('uploads/avatars/' . $user->avatar));
            }

            $validated['avatar'] = $filename;
        }

        $user->update($validated);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
