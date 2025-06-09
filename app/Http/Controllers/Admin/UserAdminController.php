<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class UserAdminController extends Controller
{
    // ✅ Tampilkan daftar user (kecuali admin) dengan fitur pencarian
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::where('role', '!=', 'admin');

        if ($search) {
            $users = $users->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $users->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    // ✅ Form edit user (nama + email + role)
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // ✅ Update data user (dengan avatar opsional)
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'   => ['required', 'string', 'max:255'],
            'email'  => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role'   => ['required', Rule::in(['user', 'staff', 'admin'])],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], // max 2MB
        ]);

        // Cegah downgrade admin
        if ($user->role === 'admin' && $request->role !== 'admin') {
            return redirect()->back()->with('error', 'Tidak bisa menurunkan role admin.');
        }

        // Upload avatar jika ada
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            $avatarFile = $request->file('avatar');
            $avatarName = uniqid() . '.' . $avatarFile->getClientOriginalExtension();

            // Simpan file ke disk 'public' di folder avatars
            $avatarFile->storeAs('avatars', $avatarName, 'public');

            $user->avatar = $avatarName;
        }

        // Update data user
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    // ✅ Hapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Tidak bisa menghapus admin.');
        }

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus akun sendiri.');
        }

        // Hapus avatar jika ada
        if ($user->avatar && Storage::disk('public')->exists('avatars/' . $user->avatar)) {
            Storage::disk('public')->delete('avatars/' . $user->avatar);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
