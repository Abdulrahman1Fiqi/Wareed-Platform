<?php

namespace App\Notifications;

use App\Models\BloodRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BloodRequestNotification extends Notification
{
    use Queueable;

    public function __construct(public BloodRequest $bloodRequest) {}

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'blood_request_id' => $this->bloodRequest->id,
            'hospital_name'    => $this->bloodRequest->hospital->name,
            'blood_type'       => $this->bloodRequest->blood_type,
            'urgency'          => $this->bloodRequest->urgency,
            'city'             => $this->bloodRequest->hospital->city,
            'district'         => $this->bloodRequest->hospital->district,
            'contact_person'   => $this->bloodRequest->contact_person,
            'contact_phone'    => $this->bloodRequest->contact_phone,
            'expires_at'       => $this->bloodRequest->expires_at->toDateTimeString(),
            'message'          => 'Emergency blood request for ' . $this->bloodRequest->blood_type . ' at ' . $this->bloodRequest->hospital->name,
        ];
    }
}