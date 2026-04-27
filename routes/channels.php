<?php

use App\Models\Hospital;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('donor.{id}', function (User $user, int $id) {
    return $user->id === $id;
});

Broadcast::channel('hospital.{id}', function ($user, int $id) {
    $hospital = auth('hospital')->user();

    if ($hospital instanceof Hospital) {
        return $hospital->id === $id;
    }
    
    return false;
});