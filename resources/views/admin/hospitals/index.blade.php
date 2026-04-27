@extends('layouts.app')

@section('title', 'Hospitals')

@section('nav-links')
    <a href="{{ route('admin.dashboard') }}" class="text-red-100 hover:text-white text-sm">Dashboard</a>
    <a href="{{ route('admin.users.index') }}" class="text-red-100 hover:text-white text-sm">Donors</a>
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

    <h1 class="text-xl font-bold text-gray-900">Hospitals</h1>

    <div class="flex gap-2">
        <a href="{{ route('admin.hospitals.index') }}"
            class="{{ !request('status') ? 'btn-primary' : 'btn-secondary' }} text-sm py-1.5 px-3">All</a>
        <a href="{{ route('admin.hospitals.index') }}?status=pending"
            class="{{ request('status') === 'pending' ? 'btn-primary' : 'btn-secondary' }} text-sm py-1.5 px-3">Pending</a>
        <a href="{{ route('admin.hospitals.index') }}?status=approved"
            class="{{ request('status') === 'approved' ? 'btn-primary' : 'btn-secondary' }} text-sm py-1.5 px-3">Approved</a>
        <a href="{{ route('admin.hospitals.index') }}?status=rejected"
            class="{{ request('status') === 'rejected' ? 'btn-primary' : 'btn-secondary' }} text-sm py-1.5 px-3">Rejected</a>
    </div>

    <div class="space-y-2">
        @forelse ($hospitals as $hospital)
            <div class="card flex justify-between items-center">
                <div>
                    <p class="font-semibold text-gray-800">{{ $hospital->name }}</p>
                    <p class="text-sm text-gray-500">
                        {{ $hospital->city }}, {{ $hospital->district }}
                        · {{ $hospital->email }}
                        · registered {{ $hospital->created_at->diffForHumans() }}
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="
                        {{ $hospital->status === 'approved' ? 'status-active' : '' }}
                        {{ $hospital->status === 'pending' ? 'status-urgent' : '' }}
                        {{ $hospital->status === 'rejected' ? 'status-cancelled' : '' }}
                        {{ $hospital->status === 'suspended' ? 'status-expired' : '' }}
                    ">{{ ucfirst($hospital->status) }}</span>
                    <a href="{{ route('admin.hospitals.show', $hospital) }}" class="btn-secondary text-sm">View</a>
                </div>
            </div>
        @empty
            <div class="card text-center py-12 text-gray-400">No hospitals found.</div>
        @endforelse
    </div>

    <div>{{ $hospitals->links() }}</div>
</div>
@endsection