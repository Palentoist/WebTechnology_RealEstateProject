<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // Show the auth page
    public function showAuth()
    {
        if (Auth::check()) {
            if (Auth::user()->role === 'admin') {
                return redirect()->route('dashboard');
            }
            return redirect()->route('customer.dashboard');
        }
        return view('auth');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'login',
                'title' => 'User logged in',
                'details' => Auth::user()->name . ' logged in at ' . now()->toDateTimeString(),
            ]);

            if (Auth::user()->role === 'admin') {
                return redirect()->intended('dashboard');
            }

            return redirect()->intended(route('customer.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Handle registration
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Password::min(8)],
            'role' => ['required', 'in:admin,customer'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            // The User model already has a "hashed" cast for password,
            // so we store the plain value here and let Laravel hash it.
            'password' => $validated['password'],
            'role' => $validated['role'],
        ]);

        Auth::login($user);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'register',
            'title' => 'New user registered',
            'details' => $user->name . ' registered at ' . now()->toDateTimeString(),
        ]);

        if ($user->role === 'admin') {
            return redirect()->route('dashboard');
        }

        return redirect()->route('customer.dashboard');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
