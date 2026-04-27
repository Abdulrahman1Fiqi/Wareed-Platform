@extends('layouts.app')

@section('title', 'Hospital Detail')

@section('nav-links')
    <a href="{{ route('admin.hospitals.index') }}" class="text-red-100 hover:text-white text-sm">← Hospitals</a>
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
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $hospital->name }}</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $hospital->city }}, {{ $hospital->district }}</p>
            </div>
            <span class="
                {{ $hospital->status === 'approved' ? 'status-active' : '' }}
                {{ $hospital->status === 'pending' ? 'status-urgent' : '' }}
                {{ $hospital->status === 'rejected' ? 'status-cancelled' : '' }}
            ">{{ ucfirst($hospital->status) }}</span>
        </div>

        <div class="grid grid-cols-2 gap-x-8 gap-y-2 text-sm mt-4">
            <div class="text-gray-500">Email</div><div class="font-medium">{{ $hospital->email }}</div>
            <div class="text-gray-500">Phone</div><div class="font-medium">{{ $hospital->phone }}</div>
            <div class="text-gray-500">License</div><div class="font-medium">{{ $hospital->license_number }}</div>
            <div class="text-gray-500">Registered</div><div class="font-medium">{{ $hospital->created_at->format('d M Y') }}</div>
            @if ($hospital->approved_at)
                <div class="text-gray-500">Approved</div><div class="font-medium">{{ $hospital->approved_at->format('d M Y') }}</div>
            @endif
        </div>

        @if ($hospital->status === 'pending')
            <div class="flex gap-3 mt-6 border-t pt-4">
                <form method="POST" action="{{ route('admin.hospitals.approve', $hospital) }}">
                    @csrf
                    <button type="submit" class="btn-primary">✅ Approve Hospital</button>
                </form>
                <form method="POST" action="{{ route('admin.hospitals.reject', $hospital) }}">
                    @csrf
                    <button type="submit" class="btn-danger">❌ Reject</button>
                </form>
            </div>
        @endif
    </div>

    <div>
        <h2 class="text-lg font-semibold text-gray-900 mb-3">
            Blood Requests ({{ $hospital->bloodRequests->count() }})
        </h2>
        <div class="space-y-2">
            @forelse ($hospital->bloodRequests as $req)
                <div class="card flex justify-between items-center py-3">
                    <div class="flex items-center gap-2">
                        <span class="bg-blood-500 text-white text-sm font-bold px-2 py-0.5 rounded">{{ $req->blood_type }}</span>
                        <span class="text-sm text-gray-600">{{ $req->created_at->format('d M Y') }}</span>
                    </div>
                    <span class="status-{{ $req->status === 'active' ? 'active' : 'fulfilled' }}">
                        {{ ucfirst(str_replace('_', ' ', $req->status)) }}
                    </span>
                </div>
            @empty
                <div class="card text-center py-6 text-gray-400 text-sm">No requests yet.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection