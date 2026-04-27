@extends('layouts.app')

@section('title', 'Donors')

@section('nav-links')
    <a href="{{ route('admin.dashboard') }}" class="text-red-100 hover:text-white text-sm">Dashboard</a>
    <a href="{{ route('admin.hospitals.index') }}" class="text-red-100 hover:text-white text-sm">Hospitals</a>
    <a href="{{ route('admin.requests.index') }}" class="text-red-100 hover:text-white text-sm">Requests</a>
@endsection

@section('nav-logout')
    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit" class="text-red-100 hover:text-white text-sm">Logout</button>
    </form>
@endsection

@section('content')
<div class="space-y-4">
    <h1 class="text-xl font-bold text-gray-900">All Donors</h1>

    <div class="space-y-2">
        @forelse ($donors as $donor)
            <div class="card flex justify-between items-center">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="bg-blood-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                            {{ $donor->blood_type }}
                        </span>
                        <p class="font-semibold text-gray-800">{{ $donor->name }}</p>
                        <span class="{{ $donor->badgeClass() }}">{{ $donor->badge() }}</span>
                    </div>
                    <p class="text-sm text-gray-500 mt-0.5">
                        {{ $donor->city }}, {{ $donor->district }}
                        · {{ $donor->donation_count }} donations
                        · {{ ucfirst($donor->status) }}
                    </p>
                </div>
                <a href="{{ route('admin.users.show', $donor) }}" class="btn-secondary text-sm">View</a>
            </div>
        @empty
            <div class="card text-center py-12 text-gray-400">No donors yet.</div>
        @endforelse
    </div>

    <div>{{ $donors->links() }}</div>
</div>
@endsection