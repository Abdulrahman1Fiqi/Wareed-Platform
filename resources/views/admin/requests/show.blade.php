@extends('layouts.app')

@section('title', 'Request Detail')

@section('nav-links')
    <a href="{{ route('admin.requests.index') }}" class="text-red-100 hover:text-white text-sm">← Requests</a>
@endsection

@section('nav-logout')
    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit" class="text-red-100 hover:text-white text-sm">Logout</button>
    </form>
@endsection

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="card">
        <div class="flex items-center gap-3 mb-4">
            <span class="bg-blood-500 text-white text-2xl font-bold px-3 py-1 rounded-lg">{{ $bloodRequest->blood_type }}</span>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Blood Request</h1>
                <div class="flex items-center gap-2 mt-1">
                    <span class="status-{{ $bloodRequest->urgency }}">{{ ucfirst($bloodRequest->urgency) }}</span>
                    <span class="status-{{ $bloodRequest->status === 'active' ? 'active' : ($bloodRequest->status === 'fulfilled' ? 'fulfilled' : 'expired') }}">
                        {{ ucfirst(str_replace('_', ' ', $bloodRequest->status)) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-x-8 gap-y-2 text-sm">
            <div class="text-gray-500">Hospital</div>
            <div class="font-medium">{{ $bloodRequest->hospital->name }}</div>
            <div class="text-gray-500">Units needed</div>
            <div class="font-medium">{{ $bloodRequest->units_needed }}</div>
            <div class="text-gray-500">Contact</div>
            <div class="font-medium">{{ $bloodRequest->contact_person }} · {{ $bloodRequest->contact_phone }}</div>
            <div class="text-gray-500">Expires</div>
            <div class="font-medium">{{ $bloodRequest->expires_at->diffForHumans() }}</div>
            @if ($bloodRequest->notes)
                <div class="text-gray-500">Notes</div>
                <div class="font-medium">{{ $bloodRequest->notes }}</div>
            @endif
        </div>
    </div>

    <div>
        <h2 class="text-lg font-semibold text-gray-900 mb-3">
            Donor Responses ({{ $bloodRequest->donorResponses->count() }})
        </h2>
        <div class="space-y-2">
            @forelse ($bloodRequest->donorResponses as $response)
                <div class="card flex justify-between items-center py-3">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="bg-blood-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                                {{ $response->donor->blood_type }}
                            </span>
                            <span class="font-medium text-gray-800 text-sm">{{ $response->donor->name }}</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $response->donor->city }}</p>
                    </div>
                    <span class="
                        {{ $response->status === 'accepted' ? 'status-active' : '' }}
                        {{ $response->status === 'confirmed' ? 'status-fulfilled' : '' }}
                        {{ $response->status === 'declined' ? 'status-cancelled' : '' }}
                        {{ $response->status === 'notified' ? 'status-expired' : '' }}
                    ">{{ ucfirst($response->status) }}</span>
                </div>
            @empty
                <div class="card text-center py-6 text-gray-400 text-sm">No responses yet.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection