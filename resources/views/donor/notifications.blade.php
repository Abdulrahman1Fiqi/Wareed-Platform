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

    <div class="space-y-3" id="notifications-list">
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
            <div class="card text-center py-12 text-gray-400 empty-state">
                <div class="text-4xl mb-2">🔔</div>
                <p>No notifications yet.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">{{ $notifications->links() }}</div>
</div>


@endsection

@push('scripts')
<script>
    window.addEventListener('load', function () {
        if (typeof window.Echo === 'undefined') return;

        window.Echo.private('donor.{{ auth()->user()->id }}')
            .notification(function (notification) {
                prependNotification(notification);
                updateUnreadCount();
            })
            .listen('.blood-request.created', function (data) {
                const notification = {
                    id:         'live-' + Date.now(),
                    data: {
                        message:          data.message,
                        blood_type:       data.blood_type,
                        urgency:          data.urgency,
                        blood_request_id: data.blood_request_id,
                    },
                    created_at: 'Just now',
                    read_at:    null,
                };
                prependNotification(notification);
            });
    });

    function prependNotification(notification) {
        const container = document.getElementById('notifications-list');
        if (!container) return;

        const card = document.createElement('div');
        card.id    = 'notification-' + notification.id;
        card.className = 'card border-l-4 border-blood-500';
        card.innerHTML = `
            <div class="flex justify-between items-start">
                <div>
                    <span class="inline-block w-2 h-2 bg-blood-500 rounded-full mr-2"></span>
                    <span class="text-sm font-semibold text-gray-800">
                        ${notification.data?.message ?? 'New blood request'}
                    </span>
                    <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                        ${notification.data?.blood_type
                            ? `<span class="bg-red-600 text-white font-bold px-1.5 py-0.5 rounded">${notification.data.blood_type}</span>`
                            : ''}
                        ${notification.data?.urgency
                            ? `<span class="status-${notification.data.urgency}">${notification.data.urgency}</span>`
                            : ''}
                        <span>Just now</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    ${notification.data?.blood_request_id
                        ? `<a href="/donor/requests/${notification.data.blood_request_id}" class="btn-primary text-xs py-1 px-3">View</a>`
                        : ''}
                </div>
            </div>
        `;

        const emptyState = container.querySelector('.empty-state');
        if (emptyState) emptyState.remove();

        container.prepend(card);

        card.style.opacity = '0';
        card.style.transform = 'translateY(-10px)';
        card.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        requestAnimationFrame(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        });
    }

    function updateUnreadCount() {
        const badge = document.getElementById('unread-badge');
        if (!badge) return;
        const current = parseInt(badge.textContent) || 0;
        badge.textContent = current + 1;
        badge.style.display = 'inline-block';
    }
</script>
@endpush