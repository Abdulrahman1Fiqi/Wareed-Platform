@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('nav-links')
    <a href="{{ route('admin.hospitals.index') }}" class="text-red-100 hover:text-white text-sm">Hospitals</a>
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
<div class="space-y-6">

    <h1 class="text-2xl font-bold text-gray-900">Platform Overview</h1>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="card text-center">
            <div class="text-3xl font-bold text-blood-500">{{ $stats['total_donors'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Total Donors</div>
        </div>
        <div class="card text-center">
            <div class="text-3xl font-bold text-green-600">{{ $stats['available_donors'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Available</div>
        </div>
        <div class="card text-center">
            <div class="text-3xl font-bold text-blue-600">{{ $stats['total_hospitals'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Hospitals</div>
        </div>
        <div class="card text-center">
            <div class="text-3xl font-bold text-orange-500">{{ $stats['pending_hospitals'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Pending Approval</div>
        </div>
        <div class="card text-center">
            <div class="text-3xl font-bold text-gray-700">{{ $stats['total_requests'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Total Requests</div>
        </div>
        <div class="card text-center">
            <div class="text-3xl font-bold text-green-500">{{ $stats['active_requests'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Active Requests</div>
        </div>
        <div class="card text-center">
            <div class="text-3xl font-bold text-blue-500">{{ $stats['fulfilled_requests'] }}</div>
            <div class="text-xs text-gray-500 mt-1">Fulfilled</div>
        </div>
    </div>

    @if ($stats['pending_hospitals'] > 0)
        <div class="bg-orange-50 border border-orange-200 rounded-xl p-4">
            <p class="text-orange-700 font-semibold text-sm">
                ⚠️ {{ $stats['pending_hospitals'] }} hospital(s) waiting for approval
                <a href="{{ route('admin.hospitals.index') }}?status=pending" class="underline ml-2">Review now →</a>
            </p>
        </div>
    @endif

    <div class="grid md:grid-cols-2 gap-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Pending Hospitals</h2>
            <div class="space-y-2">
                @forelse ($pendingHospitals as $hospital)
                    <div class="card flex justify-between items-center py-3">
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">{{ $hospital->name }}</p>
                            <p class="text-xs text-gray-400">{{ $hospital->city }} · {{ $hospital->created_at->diffForHumans() }}</p>
                        </div>
                        <a href="{{ route('admin.hospitals.show', $hospital) }}" class="btn-primary text-xs py-1 px-3">Review</a>
                    </div>
                @empty
                    <div class="card text-center py-6 text-gray-400 text-sm">No pending hospitals.</div>
                @endforelse
            </div>
        </div>

        <div>
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Recent Requests</h2>
            <div class="space-y-2">
                @forelse ($recentRequests as $req)
                    <div class="card flex justify-between items-center py-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="bg-blood-500 text-white text-xs font-bold px-1.5 py-0.5 rounded">{{ $req->blood_type }}</span>
                                <span class="text-sm font-medium text-gray-700">{{ $req->hospital->name }}</span>
                            </div>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $req->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="status-{{ $req->status === 'active' ? 'active' : 'fulfilled' }}">
                            {{ ucfirst($req->status) }}
                        </span>
                    </div>
                @empty
                    <div class="card text-center py-6 text-gray-400 text-sm">No requests yet.</div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection