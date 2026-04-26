@extends('layouts.app')

@section('title', 'Request Detail')

@section('content')
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
<div id="live-responses"></div>

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

<script>
document.addEventListener('DOMContentLoaded', function () {
    let attempts = 0;

    function subscribeWhenReady() {
        attempts++;

        if (typeof window.Echo === 'undefined' || attempts > 20) {
            return;
        }

        const state = window.Echo.connector.pusher.connection.state;

        if (state !== 'connected') {
            setTimeout(subscribeWhenReady, 500);
            return;
        }

        window.Echo.private('hospital.{{ auth("hospital")->user()->id }}')
            .listen('.donor.responded', function (data) {
                const container = document.getElementById('live-responses');

                if (data.blood_request_id !== {{ $bloodRequest->id }}) return;

                const card = document.createElement('div');
                card.style.border = '1px solid green';
                card.style.padding = '12px';
                card.style.marginBottom = '8px';
                card.innerHTML = `
                    <strong>✅ ${data.donor_name}</strong>
                    — ${data.donor_blood_type}<br>
                    Phone: ${data.donor_phone}<br>
                    Status: ${data.status}
                `;
                container.prepend(card);
            });

        console.log('Hospital channel subscribed successfully.');
    }

    subscribeWhenReady();
});
</script>
@endsection