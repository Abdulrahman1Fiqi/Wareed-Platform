<?php

namespace App\Events;

use App\Models\BloodRequest;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BloodRequestCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public BloodRequest $bloodRequest,
        public User $donor
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('donor.' . $this->donor->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'blood-request.created';
    }

    public function broadcastWith(): array
    {
        return [
            'blood_request_id' => $this->bloodRequest->id,
            'hospital_name'    => $this->bloodRequest->hospital->name,
            'blood_type'       => $this->bloodRequest->blood_type,
            'urgency'          => $this->bloodRequest->urgency,
            'city'             => $this->bloodRequest->hospital->city,
            'expires_at'       => $this->bloodRequest->expires_at->toDateTimeString(),
            'message'          => 'New ' . $this->bloodRequest->blood_type . ' blood request from ' . $this->bloodRequest->hospital->name,
        ];
    }
}