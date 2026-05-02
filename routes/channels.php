<?php

use App\Models\Hospital;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('donor.{id}', function ($user, int $id) {
    if ($user instanceof User && $user->isDonor()) {
        return (int) $user->id === (int) $id;
    }
    return false;
});

Broadcast::channel('hospital.{id}', function ($user, int $id) {
    if ($user instanceof Hospital) {
        return (int) $user->id === (int) $id;
    }
    return false;
});