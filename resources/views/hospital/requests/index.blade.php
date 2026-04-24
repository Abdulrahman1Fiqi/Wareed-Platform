<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wareed — All Requests</title>
</head>
<body>
    <h1>All Blood Requests</h1>

    <a href="{{ route('hospital.requests.create') }}">+ New Request</a>
    <a href="{{ route('hospital.dashboard') }}">← Dashboard</a>

    @forelse ($requests as $req)
        <div>
            <strong>{{ $req->blood_type }}</strong>
            — {{ $req->units_needed }} units
            — {{ $req->urgency }}
            — <em>{{ $req->status }}</em>
            — {{ $req->donor_responses_count }} responses
            — {{ $req->created_at->format('d M Y') }}
            <a href="{{ route('hospital.requests.show', $req) }}">View</a>
        </div>
    @empty
        <p>No requests yet.</p>
    @endforelse

    {{ $requests->links() }}
</body>
</html>