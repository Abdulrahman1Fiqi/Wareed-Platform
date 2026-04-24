<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wareed — Request Detail</title>
</head>
<body>
    <a href="{{ route('hospital.requests.index') }}">← All Requests</a>

    <h1>Blood Request — {{ $bloodRequest->blood_type }}</h1>

    <p>Status: {{ $bloodRequest->status }}</p>
    <p>Urgency: {{ $bloodRequest->urgency }}</p>
    <p>Units needed: {{ $bloodRequest->units_needed }}</p>
    <p>Contact: {{ $bloodRequest->contact_person }} — {{ $bloodRequest->contact_phone }}</p>
    <p>Expires: {{ $bloodRequest->expires_at->diffForHumans() }}</p>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if ($bloodRequest->isActive())
        <form method="POST" action="{{ route('hospital.requests.updateStatus', $bloodRequest) }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="fulfilled">
            <button type="submit">Mark as Fulfilled</button>
        </form>

        <form method="POST" action="{{ route('hospital.requests.updateStatus', $bloodRequest) }}">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="cancelled">
            <button type="submit">Cancel Request</button>
        </form>
    @endif

    <hr>

    <h2>Accepted Donors ({{ $responses['accepted']->count() }})</h2>
    @forelse ($responses['accepted'] as $response)
        <div>
            <strong>{{ $response->donor->name }}</strong>
            — {{ $response->donor->blood_type }}
            — {{ $response->donor->phone }}
            @if ($bloodRequest->isActive())
                <form method="POST" action="{{ route('hospital.requests.confirmDonation', [$bloodRequest, $response]) }}">
                    @csrf
                    <button type="submit">Confirm Donation</button>
                </form>
            @endif
        </div>
    @empty
        <p>No donors have accepted yet.</p>
    @endforelse

    <h2>Confirmed Donors ({{ $responses['confirmed']->count() }})</h2>
    @forelse ($responses['confirmed'] as $response)
        <div>
            <strong>{{ $response->donor->name }}</strong>
            — confirmed at {{ $response->confirmed_at->format('d M Y H:i') }}
        </div>
    @empty
        <p>None yet.</p>
    @endforelse

    <h2>Notified ({{ $responses['notified']->count() }})</h2>
    <h2>Declined ({{ $responses['declined']->count() }})</h2>
</body>
</html>