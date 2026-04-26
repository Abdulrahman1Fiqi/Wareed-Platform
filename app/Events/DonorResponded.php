<?php

namespace App\Events;

use App\Models\DonorResponse;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DonorResponded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public DonorResponse $donorResponse) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('hospital.' . $this->donorResponse->bloodRequest->hospital_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'donor.responded';
    }

    public function broadcastWith(): array
    {
        return [
            'donor_name'       => $this->donorResponse->donor->name,
            'donor_phone'      => $this->donorResponse->donor->phone,
            'donor_blood_type' => $this->donorResponse->donor->blood_type,
            'status'           => $this->donorResponse->status,
            'blood_request_id' => $this->donorResponse->blood_request_id,
        ];
    }
}