<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wareed — Donor Detail</title>
</head>
<body>
    <a href="{{ route('admin.users.index') }}">← All Donors</a>

    <h1>{{ $user->name }}</h1>
    <p>Email: {{ $user->email }}</p>
    <p>Phone: {{ $user->phone }}</p>
    <p>Blood Type: {{ $user->blood_type }}</p>
    <p>City: {{ $user->city }}, {{ $user->district }}</p>
    <p>Status: {{ $user->status }}</p>
    <p>Donations: {{ $user->donation_count }}</p>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if ($user->status !== 'unavailable')
        <form method="POST" action="{{ route('admin.users.suspend', $user) }}">
            @csrf
            <button type="submit">Suspend Donor</button>
        </form>
    @endif

    <h2>Donation History</h2>
    @forelse ($user->donorResponses as $response)
        <div>
            {{ $response->bloodRequest->blood_type }}
            — {{ $response->status }}
            — {{ $response->created_at->format('d M Y') }}
        </div>
    @empty
        <p>No donation history.</p>
    @endforelse
</body>
</html>