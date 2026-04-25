<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wareed — Request Detail</title>
</head>
<body>
    <a href="{{ route('admin.requests.index') }}">← All Requests</a>

    <h1>Request — {{ $bloodRequest->blood_type }}</h1>
    <p>Hospital: {{ $bloodRequest->hospital->name }}</p>
    <p>Urgency: {{ $bloodRequest->urgency }}</p>
    <p>Units needed: {{ $bloodRequest->units_needed }}</p>
    <p>Status: {{ $bloodRequest->status }}</p>
    <p>Expires: {{ $bloodRequest->expires_at->diffForHumans() }}</p>
    <p>Contact: {{ $bloodRequest->contact_person }} — {{ $bloodRequest->contact_phone }}</p>

    <h2>Donor Responses ({{ $bloodRequest->donorResponses->count() }})</h2>
    @forelse ($bloodRequest->donorResponses as $response)
        <div>
            <strong>{{ $response->donor->name }}</strong>
            — {{ $response->donor->blood_type }}
            — {{ $response->status }}
        </div>
    @empty
        <p>No responses yet.</p>
    @endforelse
</body>
</html>