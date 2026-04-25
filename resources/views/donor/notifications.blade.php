<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wareed — Notifications</title>
</head>
<body>
    <h1>Notifications</h1>
    <a href="{{ route('donor.dashboard') }}">← Dashboard</a>

    @forelse ($notifications as $notification)
        <div style="{{ $notification->read_at ? '' : 'font-weight:bold' }}">
            <p>{{ $notification->data['message'] ?? 'New blood request' }}</p>
            <p>{{ $notification->created_at->diffForHumans() }}</p>

            @if (!$notification->read_at)
                <form method="POST" action="{{ route('donor.notifications.read', $notification->id) }}">
                    @csrf
                    <button type="submit">Mark as Read</button>
                </form>
            @endif
        </div>
    @empty
        <p>No notifications yet.</p>
    @endforelse

    {{ $notifications->links() }}
</body>
</html>