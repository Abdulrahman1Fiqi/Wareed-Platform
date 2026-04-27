@extends('layouts.app')

@section('title', 'Blood Request')

@section('nav-links')
    <a href="{{ route('donor.dashboard') }}" class="text-red-100 hover:text-white text-sm">← Dashboard</a>
@endsection

@section('nav-logout')
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="text-red-100 hover:text-white text-sm">Logout</button>
    </form>
@endsection

@section('content')
<div class="max-w-xl mx-auto space-y-4">

    <div class="card border-l-4 border-blood-500">
        <div class="flex items-center gap-3 mb-4">
            <span class="bg-blood-500 text-white text-2xl font-bold px-3 py-1 rounded-lg">
                {{ $bloodRequest->blood_type }}
            </span>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Emergency Blood Request</h1>
                <span class="status-{{ $bloodRequest->urgency }}">{{ ucfirst($bloodRequest->urgency) }}</span>
            </div>
        </div>

        <div class="space-y-2 text-sm text-gray-700">
            <div class="flex justify-between">
                <span class="text-gray-500">Hospital</span>
                <span class="font-medium">{{ $bloodRequest->hospital->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Location</span>
                <span class="font-medium">{{ $bloodRequest->hospital->city }}, {{ $bloodRequest->hospital->district }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Units Needed</span>
                <span class="font-medium">{{ $bloodRequest->units_needed }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Expires</span>
                <span class="font-medium text-red-600">{{ $bloodRequest->expires_at->diffForHumans() }}</span>
            </div>
            @if ($bloodRequest->notes)
                <div class="flex justify-between">
                    <span class="text-gray-500">Notes</span>
                    <span class="font-medium">{{ $bloodRequest->notes }}</span>
                </div>
            @endif
        </div>
    </div>

    @if ($existingResponse->status === 'notified')
        <div class="card">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Your Response</h2>
            <div class="grid grid-cols-2 gap-3">
                <form method="POST" action="{{ route('donor.requests.respond.submit', $bloodRequest) }}">
                    @csrf
                    <input type="hidden" name="action" value="accepted">
                    <button type="submit" class="btn-primary w-full text-center">
                        ✅ Accept — I will donate
                    </button>
                </form>
                <form method="POST" action="{{ route('donor.requests.respond.submit', $bloodRequest) }}">
                    @csrf
                    <input type="hidden" name="action" value="declined">
                    <button type="submit" class="btn-secondary w-full text-center">
                        ❌ Decline
                    </button>
                </form>
            </div>
        </div>
    @else
        <div class="card text-center py-6">
            <p class="text-gray-600">
                You have <strong>{{ $existingResponse->status }}</strong> this request.
            </p>
        </div>
    @endif

    @if ($existingResponse->status === 'accepted' || $existingResponse->status === 'confirmed')
        <div class="card border border-green-200 bg-green-50">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">🏥 Hospital Contact</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Contact Person</span>
                    <span class="font-medium">{{ $bloodRequest->contact_person }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Phone</span>
                    <a href="tel:{{ $bloodRequest->contact_phone }}"
                        class="font-semibold text-blood-500">
                        {{ $bloodRequest->contact_phone }}
                    </a>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection