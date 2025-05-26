<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserAdminController extends Controller
{
    // ✅ Tampilkan daftar user
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // ✅ Form edit user (nama + email + role)
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // ✅ Update data user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['user', 'staff', 'admin'])],
        ]);

        // Cegah downgrade admin
        if ($user->role === 'admin' && $request->role !== 'admin') {
            return redirect()->back()->with('error', 'Tidak bisa menurunkan role admin.');
        }

        // Update
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

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

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
