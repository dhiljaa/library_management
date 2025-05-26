<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // 🔐 Tampilkan Form Register
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // 🔐 Proses Register
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
           'role' => 'admin', // default role

        ]);

        Auth::login($user); // login langsung setelah register

        return redirect()->route($this->redirectByRole())->with('success', 'Registration successful');
    }

    // 🔐 Tampilkan Form Login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 🔐 Proses Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route($this->redirectByRole())->with('success', 'Login successful');
    }

    // 🔐 Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully');
    }

    // 🔄 Redirect berdasarkan role user
    protected function redirectByRole()
    {
        $role = Auth::user()->role;

        return match ($role) {
            'admin' => 'admin.dashboard',
            'staff' => 'staff.loans.index',
            default => 'home',
        };
    }
}
