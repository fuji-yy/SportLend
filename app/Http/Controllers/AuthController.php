<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        // Try to find user by email or username (name)
        $user = User::where(function($query) use ($request) {
            $query->where('email', $request->email)
                  ->orWhere('name', $request->email);
        })->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();
            
            if ($user->isAdmin()) {
                return redirect('/admin/dashboard');
            } else {
                return redirect('/peminjam/dashboard');
            }
        }

        return back()->with('error', 'Username/Email atau password salah.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:admin,peminjam',
            'password' => 'required|min:5|max:20|confirmed|regex:/^[a-zA-Z0-9*_-]+$/',
            'admin_secret_code' => 'nullable|string',
        ], [
            'password.regex' => 'Password hanya boleh mengandung huruf, angka, bintang (*), underscore (_), dan strip (-).',
        ]);

        $role = $request->role;

        if ($role === 'admin') {
            $adminCode = (string) config('registration.admin_secret_code');

            if ($adminCode === '' || $request->admin_secret_code !== $adminCode) {
                return back()
                    ->withErrors(['admin_secret_code' => 'Kode rahasia admin tidak valid.'])
                    ->withInput($request->except('password', 'password_confirmation', 'admin_secret_code'));
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);

        // Automatically login the user
        Auth::login($user);
        $request->session()->regenerate();

        return redirect($user->isAdmin() ? '/admin/dashboard' : '/peminjam/dashboard')
            ->with('success', 'Registrasi berhasil. Selamat datang!');
    }
}
