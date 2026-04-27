@extends('layouts.app')

@section('title', 'Request Detail')

@section('nav-links')
    <a href="{{ route('hospital.requests.index') }}" class="text-red-100 hover:text-white text-sm">← All Requests</a>
@endsection

@section('nav-logout')
    <form method="POST" action="{{ route('hospital.logout') }}">
        @csrf
        <button type="submit" class="text-red-100 hover:text-white text-sm">Logout</button>
    </form>
@endsection

@section('content')
<div class="space-y-6">

    <div class="card">
        <div class="flex justify-between items-start">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="bg-blood-500 text-white text-2xl font-bold px-3 py-1 rounded-lg">
                        {{ $bloodRequest->blood_type }}
                    </span>
                    <span class="status-{{ $bloodRequest->urgency }}">{{ ucfirst($bloodRequest->urgency) }}</span>
                    <span class="status-{{ $bloodRequest->status === 'active' ? 'active' : ($bloodRequest->status === 'fulfilled' ? 'fulfilled' : 'expired') }}">
                        {{ ucfirst(str_replace('_', ' ', $bloodRequest->status)) }}
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-x-8 gap-y-1 text-sm mt-3">
                    <div class="text-gray-500">Units needed</div>
                    <div class="font-medium">{{ $bloodRequest->units_needed }}</div>
                    <div class="text-gray-500">Contact</div>
                    <div class="font-medium">{{ $bloodRequest->contact_person }} · {{ $bloodRequest->contact_phone }}</div>
                    <div class="text-gray-500">Expires</div>
                    <div class="font-medium text-red-600">{{ $bloodRequest->expires_at->diffForHumans() }}</div>
                    @if ($bloodRequest->notes)
                        <div class="text-gray-500">Notes</div>
                        <div class="font-medium">{{ $bloodRequest->notes }}</div>
                    @endif
                </div>
            </div>

            @if ($bloodRequest->isActive())
                <div class="flex gap-2">
                    <form method="POST" action="{{ route('hospital.requests.updateStatus', $bloodRequest) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="fulfilled">
                        <button type="submit" class="btn-primary text-sm">Mark Fulfilled</button>
                    </form>
                    <form method="POST" action="{{ route('hospital.requests.updateStatus', $bloodRequest) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="btn-danger text-sm">Cancel</button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-4 gap-4 text-center">
        <div class="card">
            <div class="text-2xl font-bold text-gray-700">{{ $responses['notified']->count() }}</div>
            <div class="text-xs text-gray-500 mt-1">Notified</div>
        </div>
        <div class="card">
            <div class="text-2xl font-bold text-green-600">{{ $responses['accepted']->count() }}</div>
            <div class="text-xs text-gray-500 mt-1">Accepted</div>
        </div>
        <div class="card">
            <div class="text-2xl font-bold text-blue-600">{{ $responses['confirmed']->count() }}</div>
            <div class="text-xs text-gray-500 mt-1">Confirmed</div>
        </div>
        <div class="card">
            <div class="text-2xl font-bold text-gray-400">{{ $responses['declined']->count() }}</div>
            <div class="text-xs text-gray-500 mt-1">Declined</div>
        </div>
    </div>

    <div id="live-responses" class="space-y-2"></div>

    <div>
        <h2 class="text-lg font-semibold text-gray-900 mb-3">Accepted Donors</h2>
        <div class="space-y-2">
            @forelse ($responses['accepted'] as $response)
                <div class="card flex justify-between items-center">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $response->donor->name }}</p>
                        <p class="text-sm text-gray-500">
                            {{ $response->donor->blood_type }} ·
                            <a href="tel:{{ $response->donor->phone }}" class="text-blood-500">{{ $response->donor->phone }}</a>
                        </p>
                    </div>
                    @if ($bloodRequest->isActive())
                        <form method="POST" action="{{ route('hospital.requests.confirmDonation', [$bloodRequest, $response]) }}">
                            @csrf
                            <button type="submit" class="btn-primary text-sm">Confirm Donation</button>
                        </form>
                    @endif
                </div>
            @empty
                <div class="card text-center py-6 text-gray-400">No accepted donors yet.</div>
            @endforelse
        </div>
    </div>

    @if ($responses['confirmed']->count() > 0)
        <div>
            <h2 class="text-lg font-semibold text-gray-900 mb-3">✅ Confirmed Donors</h2>
            <div class="space-y-2">
                @foreach ($responses['confirmed'] as $response)
                    <div class="card flex justify-between items-center bg-green-50">
                        <div>
                            <p class="font-semibold text-gray-800">{{ $response->donor->name }}</p>
                            <p class="text-sm text-gray-500">{{ $response->donor->blood_type }}</p>
                        </div>
                        <span class="text-xs text-gray-400">{{ $response->confirmed_at->format('d M Y H:i') }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    window.addEventListener('load', function () {
        if (typeof window.Echo === 'undefined') return;

        window.Echo.private('hospital.{{ auth("hospital")->user()->id }}')
            .listen('.donor.responded', function (data) {
                if (data.blood_request_id !== {{ $bloodRequest->id }}) return;

                const container = document.getElementById('live-responses');
                const card = document.createElement('div');
                card.className = 'bg-green-50 border border-green-200 rounded-xl p-4';
                card.innerHTML = `
                    <p class="font-semibold text-gray-800">✅ ${data.donor_name} just responded</p>
                    <p class="text-sm text-gray-500">${data.donor_blood_type} · ${data.donor_phone} · ${data.status}</p>
                `;
                container.prepend(card);
            });
    });
</script>
@endpush