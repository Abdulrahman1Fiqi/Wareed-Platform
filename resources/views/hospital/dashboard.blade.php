@extends('layouts.app')

@section('title', 'Hospital Dashboard')

@section('nav-links')
    <a href="{{ route('hospital.requests.index') }}" class="text-red-100 hover:text-white text-sm">All Requests</a>
    <a href="{{ route('hospital.requests.create') }}" class="bg-white text-blood-500 hover:bg-red-50 text-sm font-semibold px-3 py-1.5 rounded-lg">+ New Request</a>
@endsection

@section('nav-logout')
    <form method="POST" action="{{ route('hospital.logout') }}">
        @csrf
        <button type="submit" class="text-red-100 hover:text-white text-sm">Logout</button>
    </form>
@endsection

@section('content')

<div class="space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ auth('hospital')->user()->name }}</h1>
        <p class="text-gray-500 text-sm">{{ auth('hospital')->user()->city }}, {{ auth('hospital')->user()->district }}</p>
    </div>

    <div class="grid grid-cols-3 gap-4">
        <div class="card text-center">
            <div class="text-3xl font-bold text-blood-500">{{ $stats['total'] }}</div>
            <div class="text-sm text-gray-500 mt-1">Total Requests</div>
        </div>
        <div class="card text-center">
            <div class="text-3xl font-bold text-green-600">{{ $stats['active'] }}</div>
            <div class="text-sm text-gray-500 mt-1">Active</div>
        </div>
        <div class="card text-center">
            <div class="text-3xl font-bold text-blue-600">{{ $stats['fulfilled'] }}</div>
            <div class="text-sm text-gray-500 mt-1">Fulfilled</div>
        </div>
    </div>

    <div>
        <h2 class="text-lg font-semibold text-gray-900 mb-3">Active Requests</h2>
        <div class="space-y-3">
            @forelse ($activeRequests as $req)
                <div class="card border-l-4 border-blood-500 flex justify-between items-center">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="bg-blood-500 text-white text-sm font-bold px-2 py-0.5 rounded">{{ $req->blood_type }}</span>
                            <span class="status-{{ $req->urgency }}">{{ ucfirst($req->urgency) }}</span>
                        </div>
                        <p class="text-sm text-gray-500">
                            {{ $req->donor_responses_count }} notified
                            · expires {{ $req->expires_at->diffForHumans() }}
                        </p>
                    </div>
                    <a href="{{ route('hospital.requests.show', $req) }}" class="btn-primary text-sm">View</a>
                </div>
            @empty
                <div class="card text-center text-gray-400 py-8">
                    <div class="text-4xl mb-2">🏥</div>
                    <p>No active requests.</p>
                    <a href="{{ route('hospital.requests.create') }}" class="btn-primary inline-block mt-3 text-sm">Create Request</a>
                </div>
            @endforelse
        </div>
    </div>

</div>

@endsection