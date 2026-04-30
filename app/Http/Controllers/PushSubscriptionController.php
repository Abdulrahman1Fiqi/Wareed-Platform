<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PushSubscription;

class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'endpoint'   => 'required|string',
            'public_key' => 'nullable|string',
            'auth_token' => 'nullable|string',
        ]);

        $subscriber = auth('hospital')->check()
            ? auth('hospital')->user()
            : auth()->user();

        if (!$subscriber) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        PushSubscription::updateOrCreate(
            [
                'subscribable_id'   => $subscriber->id,
                'subscribable_type' => get_class($subscriber),
                'endpoint'          => $request->endpoint,
            ],
            [
                'public_key'   => $request->public_key,
                'auth_token'   => $request->auth_token,
            ]
        );

        return response()->json(['success' => true]);
    }
}