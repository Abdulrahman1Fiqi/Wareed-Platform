@extends('layouts.app')

@section('title', 'All Requests')

@section('nav-links')
    <a href="{{ route('admin.dashboard') }}" class="text-red-100 hover:text-white text-sm">Dashboard</a>
    <a href="{{ route('admin.hospitals.index') }}" class="text-red-100 hover:text-white text-sm">Hospitals</a>
    <a href="{{ route('admin.users.index') }}" class="text-red-100 hover:text-white text-sm">Donors</a>
@endsection

@section('nav-logout')
    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit" class="text-red-100 hover:text-white text-sm">Logout</button>
    </form>
@endsection

@section('content')
<div class="space-y-4">
    <h1 class="text-xl font-bold text-gray-900">All Blood Requests</h1>

    <div class="space-y-2">
        @forelse ($requests as $req)
            <div class="card flex justify-between items-center">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="bg-blood-500 text-white text-sm font-bold px-2 py-0.5 rounded">{{ $req->blood_type }}</span>
                        <span class="status-{{ $req->urgency }}">{{ ucfirst($req->urgency) }}</span>
                        <span class="font-medium text-gray-700 text-sm">{{ $req->hospital->name }}</span>
                    </div>
                    <p class="text-xs text-gray-400">
                        {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                        · {{ $req->created_at->diffForHumans() }}
                    </p>
                </div>
                <a href="{{ route('admin.requests.show', $req) }}" class="btn-secondary text-sm">View</a>
            </div>
        @empty
            <div class="card text-center py-12 text-gray-400">No requests found.</div>
        @endforelse
    </div>

    <div>{{ $requests->links() }}</div>
</div>
@endsection