<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wareed — Donor Dashboard</title>
</head>
<body>
    <h1>Welcome, {{ $donor->name }}</h1>

    <nav>
        <a href="{{ route('donor.dashboard') }}">Dashboard</a>
        <a href="{{ route('donor.profile') }}">Profile</a>
        <a href="{{ route('donor.notifications') }}">Notifications</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </nav>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <section>
        <p>Blood Type: {{ $donor->blood_type }}</p>
        <p>Status: {{ $donor->status }}</p>
        <p>Badge: {{ $badge }}</p>
        <p>Total Donations: {{ $donor->donation_count }}</p>

        <form method="POST" action="{{ route('donor.availability') }}">
            @csrf
            <button type="submit">
                {{ $donor->status === 'available' ? 'Mark Unavailable' : 'Mark Available' }}
            </button>
        </form>

        @if ($errors->has('status'))
            <p>{{ $errors->first('status') }}</p>
        @endif
    </section>

    <section>
        <h2>Pending Requests ({{ $pendingResponses->count() }})</h2>
        @forelse ($pendingResponses as $response)
            <div>
                <strong>{{ $response->bloodRequest->blood_type }}</strong>
                — {{ $response->bloodRequest->urgency }}
                — {{ $response->bloodRequest->hospital->name }}
                — {{ $response->bloodRequest->hospital->city }}
                — expires {{ $response->bloodRequest->expires_at->diffForHumans() }}
                <a href="{{ route('donor.requests.respond', $response->bloodRequest) }}">View & Respond</a>
            </div>
        @empty
            <p>No pending requests.</p>
        @endforelse
    </section>

    <section>
        <h2>Donation History</h2>
        @forelse ($donationHistory as $response)
            <div>
                {{ $response->bloodRequest->blood_type }}
                — {{ $response->bloodRequest->hospital->name }}
                — {{ $response->status }}
                — {{ $response->created_at->format('d M Y') }}
            </div>
        @empty
            <p>No donation history yet.</p>
        @endforelse

        {{ $donationHistory->links() }}
    </section>
</body>
</html>