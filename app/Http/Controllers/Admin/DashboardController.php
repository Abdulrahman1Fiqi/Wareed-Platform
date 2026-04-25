<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BloodRequest;
use App\Models\Hospital;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_donors'         => User::where('role', 'donor')->count(),
            'available_donors'     => User::where('role', 'donor')->where('status', 'available')->count(),
            'total_hospitals'      => Hospital::count(),
            'pending_hospitals'    => Hospital::where('status', 'pending')->count(),
            'total_requests'       => BloodRequest::count(),
            'active_requests'      => BloodRequest::where('status', 'active')->count(),
            'fulfilled_requests'   => BloodRequest::where('status', 'fulfilled')->count(),
        ];

        $pendingHospitals = Hospital::where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentRequests = BloodRequest::with('hospital')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingHospitals', 'recentRequests'));
    }
}
