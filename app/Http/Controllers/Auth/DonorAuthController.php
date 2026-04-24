<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DonorAuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.donor-register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:8|confirmed',
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'phone'      => 'required|string|max:20',
            'city'       => 'required|string|max:100',
            'district'   => 'required|string|max:100',
        ]);

        $user = User::create([
            ...$validated,
            'role'   => 'donor',
            'status' => 'available',
        ]);

        $user->sendEmailVerificationNotification();

        auth()->login($user);

        return redirect()->route('verification.notice');
    }

    public function showLogin()
    {
        return view('auth.donor-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!auth()->attempt($credentials)) {
            return back()->withErrors([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        $request->session()->regenerate();

        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('donor.dashboard');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}