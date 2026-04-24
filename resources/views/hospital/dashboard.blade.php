<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wareed — Hospital Dashboard</title>
</head>
<body>
    <h1>Welcome, {{ auth('hospital')->user()->name }}</h1>

    <nav>
        <a href="{{ route('hospital.dashboard') }}">Dashboard</a>
        <a href="{{ route('hospital.requests.index') }}">All Requests</a>
        <a href="{{ route('hospital.requests.create') }}">New Request</a>
        <form method="POST" action="{{ route('hospital.logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </nav>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <section>
        <h2>Stats</h2>
        <p>Total Requests: {{ $stats['total'] }}</p>
        <p>Active: {{ $stats['active'] }}</p>
        <p>Fulfilled: {{ $stats['fulfilled'] }}</p>
    </section>

    <section>
        <h2>Active Requests</h2>

        @forelse ($activeRequests as $req)
            <div>
                <strong>{{ $req->blood_type }}</strong>
                — {{ $req->urgency }}
                — {{ $req->donor_responses_count }} donors notified
                — expires {{ $req->expires_at->diffForHumans() }}
                <a href="{{ route('hospital.requests.show', $req) }}">View</a>
            </div>
        @empty
            <p>No active requests.</p>
        @endforelse
    </section>
</body>
</html>