<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wareed — Donors</title>
</head>
<body>
    <h1>All Donors</h1>
    <a href="{{ route('admin.dashboard') }}">← Dashboard</a>

    @forelse ($donors as $donor)
        <div>
            <strong>{{ $donor->name }}</strong>
            — {{ $donor->blood_type }}
            — {{ $donor->city }}
            — {{ $donor->status }}
            — {{ $donor->donation_count }} donations
            <a href="{{ route('admin.users.show', $donor) }}">View</a>
        </div>
    @empty
        <p>No donors found.</p>
    @endforelse

    {{ $donors->links() }}
</body>
</html>