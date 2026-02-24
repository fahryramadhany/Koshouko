<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password tidak sesuai.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string',
            'date_of_birth' => 'required|date',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['role_id'] = 3; // Member role
        $validated['member_id'] = 'MBR' . date('YmdHis') . Str::random(3);
        $validated['status'] = 'active';

        $user = User::create($validated);

        Auth::login($user);

        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Fallback logout via GET to support clients that accidentally request /logout with GET
     * (some browsers or proxies may issue GET on form actions or bookmarks).
     * This simply delegates to the normal logout flow.
     */
    public function logoutGet(Request $request)
    {
        return $this->logout($request);
    }
}
