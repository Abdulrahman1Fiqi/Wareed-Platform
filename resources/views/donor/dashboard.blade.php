@extends('layouts.app')

@section('title', 'Donor Dashboard')

@section('content')
<h1>Welcome, {{ $donor->name }}</h1>

<nav>
    <a href="{{ route('donor.dashboard') }}">Dashboard</a>
    <a href="{{ route('donor.profile') }}">Profile</a>
    <a href="{{ route('donor.notifications') }}">Notifications</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</nav>

@if (session('success'))
    <p>{{ session('success') }}</p>
@endif

<section>
    <p>Blood Type: {{ $donor->blood_type }}</p>
    <p>Status: {{ $donor->status }}</p>
    <p>Badge: {{ $badge }}</p>
    <p>Total Donations: {{ $donor->donation_count }}</p>

    <form method="POST" action="{{ route('donor.availability') }}">
        @csrf
        <button type="submit">
            {{ $donor->status === 'available' ? 'Mark Unavailable' : 'Mark Available' }}
        </button>
    </form>

    @if ($errors->has('status'))
        <p>{{ $errors->first('status') }}</p>
    @endif
</section>

<section>
    <h2>Pending Requests ({{ $pendingResponses->count() }})</h2>
    <div id="live-requests"></div>

    @forelse ($pendingResponses as $response)
        <div>
            <strong>{{ $response->bloodRequest->blood_type }}</strong>
            — {{ $response->bloodRequest->urgency }}
            — {{ $response->bloodRequest->hospital->name }}
            — {{ $response->bloodRequest->hospital->city }}
            — expires {{ $response->bloodRequest->expires_at->diffForHumans() }}
            <a href="{{ route('donor.requests.respond', $response->bloodRequest) }}">View & Respond</a>
        </div>
    @empty
        <p>No pending requests.</p>
    @endforelse
</section>

<section>
    <h2>Donation History</h2>
    @forelse ($donationHistory as $response)
        <div>
            {{ $response->bloodRequest->blood_type }}
            — {{ $response->bloodRequest->hospital->name }}
            — {{ $response->status }}
            — {{ $response->created_at->format('d M Y') }}
        </div>
    @empty
        <p>No donation history yet.</p>
    @endforelse

    {{ $donationHistory->links() }}
</section>

@if(auth()->check())
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

        window.Echo.private('donor.{{ auth()->user()->id }}')
            .listen('.blood-request.created', function (data) {
                const container = document.getElementById('live-requests');
                const card = document.createElement('div');
                card.style.border = '2px solid red';
                card.style.padding = '12px';
                card.style.marginBottom = '8px';
                card.innerHTML = `
                    <strong>🚨 New Request — ${data.blood_type}</strong><br>
                    Hospital: ${data.hospital_name}<br>
                    Urgency: ${data.urgency}<br>
                    City: ${data.city}<br>
                    <a href="/donor/dashboard">View on Dashboard</a>
                `;
                container.prepend(card);
            });

        console.log('Donor channel subscribed successfully.');
    }

    subscribeWhenReady();
});
</script>
@endif
@endsection