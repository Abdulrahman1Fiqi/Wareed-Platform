<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wareed — Hospitals</title>
</head>
<body>
    <h1>Hospitals</h1>
    <a href="{{ route('admin.dashboard') }}">← Dashboard</a>

    <nav>
        <a href="{{ route('admin.hospitals.index') }}">All</a>
        <a href="{{ route('admin.hospitals.index') }}?status=pending">Pending</a>
        <a href="{{ route('admin.hospitals.index') }}?status=approved">Approved</a>
        <a href="{{ route('admin.hospitals.index') }}?status=rejected">Rejected</a>
    </nav>

    @forelse ($hospitals as $hospital)
        <div>
            <strong>{{ $hospital->name }}</strong>
            — {{ $hospital->city }}
            — {{ $hospital->status }}
            — {{ $hospital->created_at->format('d M Y') }}
            <a href="{{ route('admin.hospitals.show', $hospital) }}">View</a>
        </div>
    @empty
        <p>No hospitals found.</p>
    @endforelse

    {{ $hospitals->links() }}
</body>
</html>