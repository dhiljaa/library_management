<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileAdminController extends Controller
{
    /**
     * Show the form for editing the admin profile.
     */
    public function edit(Request $request)
    {
        // Dapatkan data user saat ini
        $user = $request->user();

        return view('admin.profile.edit', compact('user'));
    }

    /**
     * Update the admin profile data.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        // Validasi input
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'avatar'   => 'nullable|image|max:2048',
        ]);

        // Jika password diisi, hash dulu, kalau tidak dihapus supaya tidak overwrite
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Upload avatar jika ada, hapus avatar lama jika ada
        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }
            $avatarName = uniqid() . '.' . $request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->storeAs('avatars', $avatarName, 'public');
            $validated['avatar'] = $avatarName;
        }

        // Update user
        $user->update($validated);

        // Redirect kembali ke halaman edit dengan pesan sukses
        return redirect()->route('admin.profile.edit')->with('success', 'Profil berhasil diperbarui');
    }
}
