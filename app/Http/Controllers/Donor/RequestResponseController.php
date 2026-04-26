<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\DonorResponse;
use Illuminate\Http\Request;
use App\Events\DonorResponded;


class RequestResponseController extends Controller
{
    public function show(BloodRequest $bloodRequest)
    {
        $existingResponse = DonorResponse::where('blood_request_id', $bloodRequest->id)
            ->where('donor_id', auth()->user()->id)
            ->first();

        if (!$existingResponse) {
            abort(403, 'You were not matched to this request.');
        }

        return view('donor.requests.show', compact('bloodRequest', 'existingResponse'));
    }

    public function respond(Request $request, BloodRequest $bloodRequest)
    {
        $request->validate([
            'action' => 'required|in:accepted,declined',
        ]);

        $donorResponse = DonorResponse::where('blood_request_id', $bloodRequest->id)
            ->where('donor_id', auth()->user()->id)
            ->where('status', 'notified')
            ->first();

        if (!$donorResponse) {
            return back()->withErrors(['action' => 'This request is no longer available or you have already responded.']);
        }

        $donorResponse->update([
            'status'       => $request->action,
            'responded_at' => now(),
        ]);

        broadcast(new DonorResponded($donorResponse->load('donor', 'bloodRequest')));


        if ($request->action === 'accepted') {
            return redirect()->route('donor.dashboard')
                ->with('success', 'You have accepted the request. Please go to the hospital as soon as possible.');
        }

        return redirect()->route('donor.dashboard')
            ->with('success', 'You have declined the request.');
    }
}