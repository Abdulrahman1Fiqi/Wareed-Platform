<?php

use App\Models\Hospital;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('donor.{id}', function ($user, int $id) {
    return $user instanceof User
        && $user->isDonor()
        && (int) $user->id === $id;
});

Broadcast::channel('hospital.{id}', function ($user, int $id) {
    return $user instanceof Hospital
        && (int) $user->id === $id;
});