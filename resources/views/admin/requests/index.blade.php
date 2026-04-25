<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wareed — All Requests</title>
</head>
<body>
    <h1>All Blood Requests</h1>
    <a href="{{ route('admin.dashboard') }}">← Dashboard</a>

    @forelse ($requests as $req)
        <div>
            <strong>{{ $req->blood_type }}</strong>
            — {{ $req->hospital->name }}
            — {{ $req->urgency }}
            — {{ $req->status }}
            — {{ $req->created_at->diffForHumans() }}
            <a href="{{ route('admin.requests.show', $req) }}">View</a>
        </div>
    @empty
        <p>No requests found.</p>
    @endforelse

    {{ $requests->links() }}
</body>
</html>