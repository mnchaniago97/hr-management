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
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'nia' => 'sometimes|nullable|string|max:50|unique:users,nia,' . $user->id,
            'avatar' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'country' => 'sometimes|nullable|string|max:100',
            'city_state' => 'sometimes|nullable|string|max:150',
            'postal_code' => 'sometimes|nullable|string|max:20',
            'tax_id' => 'sometimes|nullable|string|max:50',
        ]);

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $avatar->getClientOriginalExtension();
            $path = $avatar->storeAs('avatars', $filename, 'public');

            if ($user->avatar) {
                \Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            $validated['avatar'] = basename($path);
        }

        $user->update($validated);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
