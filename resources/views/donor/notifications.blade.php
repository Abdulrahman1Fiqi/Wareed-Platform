@extends('layouts.app')

@section('title', 'Notifications')

@section('nav-links')
    <a href="{{ route('donor.dashboard') }}" class="text-red-100 hover:text-white text-sm">← Dashboard</a>
@endsection

@section('nav-logout')
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="text-red-100 hover:text-white text-sm">Logout</button>
    </form>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-xl font-bold text-gray-900 mb-4">Notifications</h1>

    <div class="space-y-3">
        @forelse ($notifications as $notification)
            <div class="card {{ $notification->read_at ? 'opacity-60' : 'border-l-4 border-blood-500' }}">
                <div class="flex justify-between items-start">
                    <div>
                        @if (!$notification->read_at)
                            <span class="inline-block w-2 h-2 bg-blood-500 rounded-full mr-2"></span>
                        @endif
                        <span class="text-sm font-semibold text-gray-800">
                            {{ $notification->data['message'] ?? 'New blood request' }}
                        </span>
                        <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                            @if (isset($notification->data['blood_type']))
                                <span class="bg-blood-500 text-white font-bold px-1.5 py-0.5 rounded">
                                    {{ $notification->data['blood_type'] }}
                                </span>
                            @endif
                            @if (isset($notification->data['urgency']))
                                <span class="status-{{ $notification->data['urgency'] }}">
                                    {{ ucfirst($notification->data['urgency']) }}
                                </span>
                            @endif
                            <span>{{ $notification->created_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        @if (isset($notification->data['blood_request_id']))
                            <a href="{{ route('donor.requests.respond', $notification->data['blood_request_id']) }}"
                                class="btn-primary text-xs py-1 px-3">
                                View
                            </a>
                        @endif
                        @if (!$notification->read_at)
                            <form method="POST" action="{{ route('donor.notifications.read', $notification->id) }}">
                                @csrf
                                <button type="submit" class="btn-secondary text-xs py-1 px-3">Mark Read</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="card text-center py-12 text-gray-400">
                <div class="text-4xl mb-2">🔔</div>
                <p>No notifications yet.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $notifications->links() }}</div>
</div>
@endsection