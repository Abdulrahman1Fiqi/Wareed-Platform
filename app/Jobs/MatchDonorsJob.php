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
use App\Models\PushSubscription;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class MatchDonorsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 120;

    public function __construct(public BloodRequest $bloodRequest) {}

    public function handle(): void
    {
        $this->bloodRequest->load('hospital');

        if (!$this->bloodRequest->isActive()) {
            return;
        }

        $compatibleTypes = $this->bloodRequest->compatibleBloodTypes();
        $hospital = $this->bloodRequest->hospital;

        $donors = User::where('role', 'donor')
            ->where('status', 'available')
            ->where('city', $hospital->city)
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
                $hospital->district
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

            $this->sendPushNotification($donor, $this->bloodRequest);
        }
    }

    private function sendPushNotification($donor, $bloodRequest): void
    {
        $subscriptions = PushSubscription::where('subscribable_id', $donor->id)
            ->where('subscribable_type', get_class($donor))
            ->get();

        if ($subscriptions->isEmpty()) return;

        $auth = [
            'VAPID' => [
                'subject'    => config('services.vapid.subject'),
                'publicKey'  => config('services.vapid.public_key'),
                'privateKey' => config('services.vapid.private_key'),
            ],
        ];

        $webPush = new WebPush($auth);

        $payload = json_encode([
            'title' => '🩸 Blood Request — ' . $bloodRequest->blood_type,
            'body'  => $bloodRequest->urgency . ' request from ' . $bloodRequest->hospital->name . ' in ' . $bloodRequest->hospital->city,
            'url'   => '/donor/dashboard',
            'icon'  => '/icons/icon-192.png',
        ]);

        foreach ($subscriptions as $sub) {
            $webPush->queueNotification(
                Subscription::create([
                    'endpoint'        => $sub->endpoint,
                    'publicKey'       => $sub->public_key,
                    'authToken'       => $sub->auth_token,
                    'contentEncoding' => $sub->content_encoding,
                ]),
                $payload
            );
        }

        $webPush->flush();
    }

}