<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserAdminController extends Controller
{
    // 👥 GET /api/admin/users
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'User list retrieved successfully',
            'data' => $users
        ]);
    }

    // 🔄 PUT /api/admin/users/{id}/role
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => ['required', Rule::in(['user', 'staff', 'admin'])],
        ]);

        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'User role updated successfully',
            'data' => $user
        ]);
    }

    // ❌ DELETE /api/admin/users/{id}
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot delete admin user'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully'
        ]);
    }
}
