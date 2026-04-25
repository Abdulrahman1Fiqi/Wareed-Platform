<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wareed — Blood Request</title>
</head>
<body>
    <a href="{{ route('donor.dashboard') }}">← Dashboard</a>

    <h1>Emergency Blood Request</h1>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <section>
        <p>Hospital: {{ $bloodRequest->hospital->name }}</p>
        <p>Blood Type Needed: {{ $bloodRequest->blood_type }}</p>
        <p>Units Needed: {{ $bloodRequest->units_needed }}</p>
        <p>Urgency: {{ $bloodRequest->urgency }}</p>
        <p>Expires: {{ $bloodRequest->expires_at->diffForHumans() }}</p>
        <p>Notes: {{ $bloodRequest->notes ?? 'None' }}</p>
    </section>

    @if ($existingResponse->status === 'notified')
        <section>
            <h2>Your Response</h2>

            <form method="POST" action="{{ route('donor.requests.respond.submit', $bloodRequest) }}">
                @csrf
                <input type="hidden" name="action" value="accepted">
                <button type="submit">Accept — I will donate</button>
            </form>

            <form method="POST" action="{{ route('donor.requests.respond.submit', $bloodRequest) }}">
                @csrf
                <input type="hidden" name="action" value="declined">
                <button type="submit">Decline</button>
            </form>
        </section>
    @else
        <p>You have already {{ $existingResponse->status }} this request.</p>
    @endif

    @if ($existingResponse->status === 'accepted')
        <section>
            <h2>Hospital Contact</h2>
            <p>Contact Person: {{ $bloodRequest->contact_person }}</p>
            <p>Phone: {{ $bloodRequest->contact_phone }}</p>
        </section>
    @endif
</body>
</html>