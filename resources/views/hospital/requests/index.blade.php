@extends('layouts.app')

@section('title', 'All Requests')

@section('nav-links')
    <a href="{{ route('hospital.dashboard') }}" class="text-red-100 hover:text-white text-sm">Dashboard</a>
    <a href="{{ route('hospital.requests.create') }}" class="bg-white text-blood-500 text-sm font-semibold px-3 py-1.5 rounded-lg">+ New Request</a>
@endsection

@section('nav-logout')
    <form method="POST" action="{{ route('hospital.logout') }}">
        @csrf
        <button type="submit" class="text-red-100 hover:text-white text-sm">Logout</button>
    </form>
@endsection

@section('content')
<div class="space-y-4">
    <h1 class="text-xl font-bold text-gray-900">All Blood Requests</h1>

    <div class="space-y-3">
        @forelse ($requests as $req)
            <div class="card flex justify-between items-center">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="bg-blood-500 text-white text-sm font-bold px-2 py-0.5 rounded">
                            {{ $req->blood_type }}
                        </span>
                        <span class="status-{{ $req->urgency }}">{{ ucfirst($req->urgency) }}</span>
                        <span class="status-{{ $req->status === 'active' ? 'active' : ($req->status === 'fulfilled' ? 'fulfilled' : ($req->status === 'expired' ? 'expired' : 'cancelled')) }}">
                            {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500">
                        {{ $req->units_needed }} units
                        · {{ $req->donor_responses_count }} responses
                        · {{ $req->created_at->format('d M Y') }}
                    </p>
                </div>
                <a href="{{ route('hospital.requests.show', $req) }}" class="btn-secondary text-sm">View</a>
            </div>
        @empty
            <div class="card text-center py-12 text-gray-400">
                <div class="text-4xl mb-2">📋</div>
                <p>No requests yet.</p>
                <a href="{{ route('hospital.requests.create') }}" class="btn-primary inline-block mt-3 text-sm">Create First Request</a>
            </div>
        @endforelse
    </div>

    <div>{{ $requests->links() }}</div>
</div>
@endsection