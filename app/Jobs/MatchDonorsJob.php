<?php

namespace App\Jobs;

use App\Models\BloodRequest;
use App\Models\DonorResponse;
use App\Models\User;
use App\Notifications\BloodRequestNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\BloodRequestCreated;

class MatchDonorsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(public BloodRequest $bloodRequest) {}

    public function handle(): void
    {
        if (!$this->bloodRequest->isActive()) {
            return;
        }

        $compatibleTypes = $this->bloodRequest->compatibleBloodTypes();

        $donors = User::where('role', 'donor')
            ->where('status', 'available')
            ->where('city', $this->bloodRequest->hospital->city)
            ->whereIn('blood_type', $compatibleTypes)
            ->where(function ($query) {
                $query->whereNull('last_donation_date')
                      ->orWhere('last_donation_date', '<=', now()->subDays(56));
            })
            ->whereNotIn('id', function ($query) {
                $query->select('donor_id')
                      ->from('donor_responses')
                      ->where('blood_request_id', $this->bloodRequest->id);
            })
            ->orderByRaw("CASE WHEN district = ? THEN 0 ELSE 1 END", [
                $this->bloodRequest->hospital->district
            ])
            ->orderBy('last_donation_date')
            ->get();

        if ($donors->isEmpty()) {
            return;
        }

        foreach ($donors as $donor) {
            DonorResponse::create([
                'blood_request_id' => $this->bloodRequest->id,
                'donor_id'         => $donor->id,
                'status'           => 'notified',
            ]);

            $donor->notify(new BloodRequestNotification($this->bloodRequest));

            broadcast(new BloodRequestCreated($this->bloodRequest, $donor));

        }
    }
}