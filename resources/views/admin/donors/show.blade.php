@extends('layouts.app')

@section('title', 'Donor Detail')

@section('nav-links')
    <a href="{{ route('admin.users.index') }}" class="text-red-100 hover:text-white text-sm">← Donors</a>
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
        <div class="flex items-center gap-4 mb-4">
            <div class="w-14 h-14 rounded-full bg-blood-500 flex items-center justify-center text-white text-xl font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $user->name }}</h1>
                <div class="flex items-center gap-2 mt-1">
                    <span class="bg-blood-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $user->blood_type }}</span>
                    <span class="{{ $user->badgeClass() }}">{{ $user->badge() }}</span>
                    <span class="{{ $user->status === 'available' ? 'status-active' : 'status-cancelled' }}">{{ ucfirst($user->status) }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-x-8 gap-y-2 text-sm">
            <div class="text-gray-500">Email</div><div class="font-medium">{{ $user->email }}</div>
            <div class="text-gray-500">Phone</div><div class="font-medium">{{ $user->phone }}</div>
            <div class="text-gray-500">City</div><div class="font-medium">{{ $user->city }}, {{ $user->district }}</div>
            <div class="text-gray-500">Donations</div><div class="font-medium">{{ $user->donation_count }}</div>
            <div class="text-gray-500">Last Donation</div>
            <div class="font-medium">{{ $user->last_donation_date ? $user->last_donation_date->format('d M Y') : 'Never' }}</div>
            <div class="text-gray-500">Registered</div><div class="font-medium">{{ $user->created_at->format('d M Y') }}</div>
        </div>

        @if ($user->status !== 'unavailable')
            <div class="border-t mt-4 pt-4">
                <form method="POST" action="{{ route('admin.users.suspend', $user) }}">
                    @csrf
                    <button type="submit" class="btn-danger text-sm">Suspend Donor</button>
                </form>
            </div>
        @endif
    </div>

    <div>
        <h2 class="text-lg font-semibold text-gray-900 mb-3">Donation History</h2>
        <div class="space-y-2">
            @forelse ($user->donorResponses as $response)
                <div class="card flex justify-between items-center py-3">
                    <div>
                        <span class="bg-blood-500 text-white text-xs font-bold px-1.5 py-0.5 rounded mr-2">
                            {{ $response->bloodRequest->blood_type }}
                        </span>
                        <span class="text-sm text-gray-700">{{ $response->bloodRequest->hospital->name ?? 'Unknown' }}</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="status-{{ $response->status === 'confirmed' ? 'fulfilled' : 'active' }}">
                            {{ ucfirst($response->status) }}
                        </span>
                        <span class="text-xs text-gray-400">{{ $response->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            @empty
                <div class="card text-center py-6 text-gray-400 text-sm">No donation history.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection