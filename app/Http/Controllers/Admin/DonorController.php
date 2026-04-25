<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class DonorController extends Controller
{
    public function index()
    {
        $donors = User::where('role', 'donor')
            ->latest()
            ->paginate(15);

        return view('admin.donors.index', compact('donors'));
    }

    public function show(User $user)
    {
        $user->load('donorResponses.bloodRequest');
        return view('admin.donors.show', compact('user'));
    }

    public function suspend(User $user)
    {
        $user->update(['status' => 'unavailable']);
        return back()->with('success', $user->name . ' has been suspended.');
    }
}
