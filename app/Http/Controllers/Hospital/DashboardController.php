<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;

class DashboardController extends Controller
{
    public function index()
    {
        $hospital = auth('hospital')->user();

        $activeRequests = BloodRequest::where('hospital_id', $hospital->id)
            ->whereIn('status', ['active', 'partially_fulfilled'])
            ->withCount('donorResponses')
            ->latest()
            ->get();

        $stats = [
            'total'     => BloodRequest::where('hospital_id', $hospital->id)->count(),
            'active'    => $activeRequests->count(),
            'fulfilled' => BloodRequest::where('hospital_id', $hospital->id)->where('status', 'fulfilled')->count(),
        ];

        return view('hospital.dashboard', compact('activeRequests', 'stats'));
    }
}