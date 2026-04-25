<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wareed — Admin Dashboard</title>
</head>
<body>
    <h1>Admin Dashboard</h1>

    <nav>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.hospitals.index') }}">Hospitals</a>
        <a href="{{ route('admin.users.index') }}">Donors</a>
        <a href="{{ route('admin.requests.index') }}">Requests</a>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </nav>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <section>
        <h2>Platform Stats</h2>
        <p>Total Donors: {{ $stats['total_donors'] }}</p>
        <p>Available Donors: {{ $stats['available_donors'] }}</p>
        <p>Total Hospitals: {{ $stats['total_hospitals'] }}</p>
        <p>Pending Approval: {{ $stats['pending_hospitals'] }}</p>
        <p>Total Requests: {{ $stats['total_requests'] }}</p>
        <p>Active Requests: {{ $stats['active_requests'] }}</p>
        <p>Fulfilled Requests: {{ $stats['fulfilled_requests'] }}</p>
    </section>

    <section>
        <h2>Pending Hospitals</h2>
        @forelse ($pendingHospitals as $hospital)
            <div>
                <strong>{{ $hospital->name }}</strong>
                — {{ $hospital->city }}
                — {{ $hospital->email }}
                <a href="{{ route('admin.hospitals.show', $hospital) }}">Review</a>
            </div>
        @empty
            <p>No pending hospitals.</p>
        @endforelse
    </section>

    <section>
        <h2>Recent Blood Requests</h2>
        @forelse ($recentRequests as $req)
            <div>
                <strong>{{ $req->blood_type }}</strong>
                — {{ $req->hospital->name }}
                — {{ $req->status }}
                — {{ $req->created_at->diffForHumans() }}
                <a href="{{ route('admin.requests.show', $req) }}">View</a>
            </div>
        @empty
            <p>No requests yet.</p>
        @endforelse
    </section>
</body>
</html>