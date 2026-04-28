@extends('layouts.app')

@section('title', 'Dashboard')

@section('nav-links')
    <a href="{{ route('donor.profile') }}" class="text-red-100 hover:text-white text-sm">Profile</a>
    <a href="{{ route('donor.notifications') }}" class="text-red-100 hover:text-white text-sm">Notifications</a>
@endsection

@section('nav-logout')
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="text-red-100 hover:text-white text-sm">Logout</button>
    </form>
@endsection

@section('content')

<div class="space-y-6">

    <div class="card flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">{{ $donor->name }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $donor->city }}, {{ $donor->district }}</p>
            <div class="flex items-center gap-2 mt-2">
                <span class="bg-blood-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $donor->blood_type }}</span>
                <span class="{{ $donor->badgeClass() }}">{{ $donor->badge() }}</span>
                <span class="{{ $donor->status === 'available' ? 'status-active' : 'status-cancelled' }}">
                    {{ ucfirst($donor->status) }}
                </span>
            </div>
        </div>
        <div class="text-center">
            <div class="text-3xl font-bold text-blood-500">{{ $donor->donation_count }}</div>
            <div class="text-xs text-gray-500">Donations</div>
            <form method="POST" action="{{ route('donor.availability') }}" class="mt-2">
                @csrf
                <button type="submit" class="{{ $donor->status === 'available' ? 'btn-secondary' : 'btn-primary' }} text-xs py-1 px-3">
                    {{ $donor->status === 'available' ? 'Go Unavailable' : 'Go Available' }}
                </button>
            </form>
        </div>
    </div>

    @if ($errors->has('status'))
        <p class="text-red-600 text-sm">{{ $errors->first('status') }}</p>
    @endif

    <div>
        <h2 class="text-lg font-semibold text-gray-900 mb-3">
            🚨 Pending Requests
            <span class="text-sm font-normal text-gray-500">({{ $pendingResponses->count() }})</span>
        </h2>

        <div id="live-requests" class="space-y-3"></div>

        <div class="space-y-3">
            @forelse ($pendingResponses as $response)
                <div class="card border-l-4 border-blood-500">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="bg-blood-500 text-white text-sm font-bold px-2 py-0.5 rounded">
                                    {{ $response->bloodRequest->blood_type }}
                                </span>
                                <span class="status-{{ $response->bloodRequest->urgency }}">
                                    {{ ucfirst($response->bloodRequest->urgency) }}
                                </span>
                            </div>
                            <p class="font-semibold text-gray-800">{{ $response->bloodRequest->hospital->name }}</p>
                            <p class="text-sm text-gray-500">{{ $response->bloodRequest->hospital->city }}, {{ $response->bloodRequest->hospital->district }}</p>
                            <p class="text-xs text-gray-400 mt-1">Expires {{ $response->bloodRequest->expires_at->diffForHumans() }}</p>
                        </div>
                        <a href="{{ route('donor.requests.respond', $response->bloodRequest) }}" class="btn-primary text-sm">
                            Respond
                        </a>
                    </div>
                </div>
            @empty
                <div class="card text-center text-gray-400 py-8">
                    <div class="text-4xl mb-2">💤</div>
                    <p>No pending requests right now.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div>
        <h2 class="text-lg font-semibold text-gray-900 mb-3">Donation History</h2>
        <div class="space-y-2">
            @forelse ($donationHistory as $response)
                <div class="card py-3 flex justify-between items-center">
                    <div>
                        <span class="bg-blood-500 text-white text-xs font-bold px-2 py-0.5 rounded mr-2">
                            {{ $response->bloodRequest->blood_type }}
                        </span>
                        {{ $response->bloodRequest->hospital->name }}
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="status-{{ $response->status === 'confirmed' ? 'fulfilled' : 'active' }}">
                            {{ ucfirst($response->status) }}
                        </span>
                        <span class="text-xs text-gray-400">{{ $response->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            @empty
                <div class="card text-center text-gray-400 py-8">
                    <p>No donation history yet.</p>
                </div>
            @endforelse
        </div>
        {{ $donationHistory->links() }}
    </div>

</div>

@endsection

@push('scripts')
<script>
    window.addEventListener('load', function () {
        if (typeof window.Echo === 'undefined') return;

        window.Echo.private('donor.{{ auth()->user()->id }}')
            .listen('.blood-request.created', function (data) {
                const container = document.getElementById('live-requests');
                const card = document.createElement('div');
                card.className = 'bg-red-50 border-l-4 border-blood-500 rounded-xl p-4 shadow-sm';
                card.innerHTML = `
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="bg-red-600 text-white text-sm font-bold px-2 py-0.5 rounded">${data.blood_type}</span>
                            <span class="text-sm font-semibold ml-2">${data.hospital_name}</span>
                            <p class="text-xs text-gray-500 mt-1">${data.urgency} · ${data.city} · expires soon</p>
                        </div>
                        <a href="/donor/dashboard" class="bg-red-600 text-white text-sm px-3 py-1 rounded-lg">Respond</a>
                    </div>
                `;
                container.prepend(card);
            });
    });
</script>
@endpush