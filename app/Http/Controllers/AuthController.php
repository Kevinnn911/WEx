<?php
// File: app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the login page.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isSiswa()) {
                return redirect()->route('siswa.dashboard');
            }
            return redirect()->route('admin.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Username atau email wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $loginInput = $credentials['login'];
        $password = $credentials['password'];

        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $attemptCredentials = [
            $fieldType => $loginInput,
            'password' => $password,
        ];

        if (Auth::attempt($attemptCredentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->isSiswa()) {
                return redirect()->intended(route('siswa.dashboard'));
            }

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'login' => 'Username/email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('login');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}
