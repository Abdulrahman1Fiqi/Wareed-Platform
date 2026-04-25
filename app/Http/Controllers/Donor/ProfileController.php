<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $donor = auth()->user();
        return view('donor.profile', compact('donor'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'phone'    => 'required|string|max:20',
            'city'     => 'required|string|max:100',
            'district' => 'required|string|max:100',
        ]);

        auth()->user()->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function toggleAvailability()
    {
        $donor = auth()->user();

        if ($donor->status === 'on_cooldown') {
            return back()->withErrors(['status' => 'You cannot change availability while on donation cooldown.']);
        }

        $newStatus = $donor->status === 'available' ? 'unavailable' : 'available';
        $donor->update(['status' => $newStatus]);

        return back()->with('success', 'Availability updated to ' . $newStatus . '.');
    }
}