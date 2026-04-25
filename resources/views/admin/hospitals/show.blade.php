<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wareed — Hospital Detail</title>
</head>
<body>
    <a href="{{ route('admin.hospitals.index') }}">← All Hospitals</a>

    <h1>{{ $hospital->name }}</h1>
    <p>Email: {{ $hospital->email }}</p>
    <p>Phone: {{ $hospital->phone }}</p>
    <p>City: {{ $hospital->city }}, {{ $hospital->district }}</p>
    <p>License: {{ $hospital->license_number }}</p>
    <p>Status: {{ $hospital->status }}</p>
    <p>Registered: {{ $hospital->created_at->format('d M Y') }}</p>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if ($hospital->status === 'pending')
        <form method="POST" action="{{ route('admin.hospitals.approve', $hospital) }}">
            @csrf
            <button type="submit">Approve</button>
        </form>

        <form method="POST" action="{{ route('admin.hospitals.reject', $hospital) }}">
            @csrf
            <button type="submit">Reject</button>
        </form>
    @endif

    <h2>Blood Requests ({{ $hospital->bloodRequests->count() }})</h2>
    @forelse ($hospital->bloodRequests as $req)
        <div>
            {{ $req->blood_type }} — {{ $req->status }} — {{ $req->created_at->format('d M Y') }}
        </div>
    @empty
        <p>No requests yet.</p>
    @endforelse
</body>
</html>