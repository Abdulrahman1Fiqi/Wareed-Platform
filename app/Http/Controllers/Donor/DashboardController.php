<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DonorResponse;

class DashboardController extends Controller
{
    public function index()
    {
        $donor = auth()->user();

        $pendingResponses = DonorResponse::where('donor_id', $donor->id)
            ->where('status', 'notified')
            ->with('bloodRequest.hospital')
            ->latest()
            ->get();

        $donationHistory = DonorResponse::where('donor_id', $donor->id)
            ->whereIn('status', ['accepted', 'confirmed'])
            ->with('bloodRequest.hospital')
            ->latest()
            ->paginate(10);


        return view('donor.dashboard', compact('donor', 'pendingResponses', 'donationHistory'));
    }

    
    
}
