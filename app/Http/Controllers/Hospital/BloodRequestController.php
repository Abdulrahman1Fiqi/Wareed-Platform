<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\DonorResponse;
use Illuminate\Http\Request;
use App\Jobs\MatchDonorsJob;
use App\Jobs\SetDonorCooldownJob;

class BloodRequestController extends Controller
{
    public function index()
    {
        $hospital = auth('hospital')->user();

        $requests = BloodRequest::where('hospital_id', $hospital->id)
            ->withCount('donorResponses')
            ->latest()
            ->paginate(10);

        return view('hospital.requests.index', compact('requests'));
    }

    public function create()
    {
        return view('hospital.requests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'blood_type'     => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'units_needed'   => 'required|integer|min:1|max:20',
            'urgency'        => 'required|in:critical,urgent,standard',
            'contact_person' => 'required|string|max:100',
            'contact_phone'  => 'required|string|max:20',
            'notes'          => 'nullable|string|max:500',
        ]);

        $expiresAt = match($validated['urgency']) {
            'critical' => now()->addHours(6),
            'urgent'   => now()->addHours(24),
            'standard' => now()->addHours(72),
        };

        $hospital = auth('hospital')->user();

        $bloodRequest = BloodRequest::create([
            ...$validated,
            'hospital_id' => $hospital->id,
            'status'      => 'active',
            'expires_at'  => $expiresAt,
        ]);

        MatchDonorsJob::dispatch($bloodRequest);

        return redirect()->route('hospital.requests.show', $bloodRequest)
            ->with('success', 'Blood request created. Matching donors are being notified.');
    }

    public function show(BloodRequest $bloodRequest)
    {
        $this->authorizeRequest($bloodRequest);

        $bloodRequest->load('donorResponses.donor');

        $responses = [
            'notified'  => $bloodRequest->donorResponses->where('status', 'notified'),
            'accepted'  => $bloodRequest->donorResponses->where('status', 'accepted'),
            'declined'  => $bloodRequest->donorResponses->where('status', 'declined'),
            'confirmed' => $bloodRequest->donorResponses->where('status', 'confirmed'),
        ];

        return view('hospital.requests.show', compact('bloodRequest', 'responses'));
    }

    public function updateStatus(Request $request, BloodRequest $bloodRequest)
    {
        $this->authorizeRequest($bloodRequest);

        $request->validate([
            'status' => 'required|in:fulfilled,cancelled',
        ]);

        $bloodRequest->update(['status' => $request->status]);

        return redirect()->route('hospital.requests.index')
            ->with('success', 'Request marked as ' . $request->status . '.');
    }

    public function confirmDonation(BloodRequest $bloodRequest, DonorResponse $donorResponse)
    {
        $this->authorizeRequest($bloodRequest);

        $donorResponse->update([
            'status'       => 'confirmed',
            'confirmed_at' => now(),
        ]);

        $donorResponse->donor->increment('donation_count');
        $donorResponse->donor->update([
            'last_donation_date' => today(),
            'status'             => 'on_cooldown',
            ]);

        SetDonorCooldownJob::dispatch($donorResponse->donor)
            ->delay(now()->addDays(56));

        $totalConfirmed = $bloodRequest->donorResponses()->where('status', 'confirmed')->count();

        if ($totalConfirmed >= $bloodRequest->units_needed) {
            $bloodRequest->update(['status' => 'fulfilled']);
        } else {
            $bloodRequest->update(['status' => 'partially_fulfilled']);
        }

        return back()->with('success', 'Donation confirmed.');
    }

    private function authorizeRequest(BloodRequest $bloodRequest)
    {
        if ($bloodRequest->hospital_id !== auth('hospital')->user()->id) {
            abort(403);
        }
    }
}