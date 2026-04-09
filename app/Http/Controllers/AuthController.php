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
            } elseif ($user->isStaff()) {
                return redirect('/petugas/dashboard');
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
            'password' => 'required|min:5|max:20|confirmed|regex:/^[a-zA-Z0-9*_-]+$/',
        ], [
            'password.regex' => 'Password hanya boleh mengandung huruf, angka, bintang (*), underscore (_), dan strip (-).',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'peminjam',
        ]);

        // Automatically login the user
        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/peminjam/dashboard')->with('success', 'Registrasi berhasil. Selamat datang!');
    }
}
