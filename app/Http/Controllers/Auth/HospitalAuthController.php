<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use Illuminate\Http\Request;

class HospitalAuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.hospital-register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:150',
            'email'          => 'required|email|unique:hospitals,email',
            'password'       => 'required|min:8|confirmed',
            'phone'          => 'required|string|max:20',
            'city'           => 'required|string|max:100',
            'district'       => 'required|string|max:100',
            'license_number' => 'required|string|unique:hospitals,license_number',
        ]);

        Hospital::create([
            ...$validated,
            'status'         => 'pending',
        ]);

        return redirect()->route('hospital.login')
            ->with('success', 'Registration submitted. Please wait for admin approval.');
    }

    public function showLogin()
    {
        return view('auth.hospital-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!auth('hospital')->attempt($credentials)) {
            return back()->withErrors([
                'email' => 'These credentials do not match our records.',
            ]);
        }

        if (!auth('hospital')->user()->isApproved()) {
            auth('hospital')->logout();
            return back()->withErrors([
                'email' => 'Your account is pending admin approval.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route('hospital.dashboard');
    }

    public function logout(Request $request)
    {
        auth('hospital')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('hospital.login');
    }
}