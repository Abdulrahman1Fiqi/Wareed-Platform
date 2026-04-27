@extends('layouts.app')

@section('title', 'My Profile')

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
<div class="max-w-xl mx-auto space-y-6">

    <div class="card">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 rounded-full bg-blood-500 flex items-center justify-center text-white text-xl font-bold">
                {{ strtoupper(substr($donor->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $donor->name }}</h1>
                <div class="flex items-center gap-2 mt-1">
                    <span class="bg-blood-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                        {{ $donor->blood_type }}
                    </span>
                    <span class="{{ $donor->badgeClass() }}">{{ $donor->badge() }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4 mb-6 text-center">
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="text-2xl font-bold text-blood-500">{{ $donor->donation_count }}</div>
                <div class="text-xs text-gray-500">Donations</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="text-sm font-semibold text-gray-700">{{ ucfirst($donor->status) }}</div>
                <div class="text-xs text-gray-500">Status</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-3">
                <div class="text-sm font-semibold text-gray-700">
                    {{ $donor->last_donation_date ? $donor->last_donation_date->format('M Y') : 'Never' }}
                </div>
                <div class="text-xs text-gray-500">Last Donation</div>
            </div>
        </div>

        <div class="border-t pt-4">
            <p class="text-xs text-gray-400 mb-1">Blood type and city are managed by the platform and cannot be changed here.</p>
        </div>
    </div>

    <div class="card">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Edit Profile</h2>

        <form method="POST" action="{{ route('donor.profile.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $donor->name) }}" required
                    class="w-full rounded-lg border-gray-300 focus:border-blood-500 focus:ring-blood-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $donor->phone) }}" required
                    class="w-full rounded-lg border-gray-300 focus:border-blood-500 focus:ring-blood-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <input type="text" name="city" value="{{ old('city', $donor->city) }}" required
                        class="w-full rounded-lg border-gray-300 focus:border-blood-500 focus:ring-blood-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">District</label>
                    <input type="text" name="district" value="{{ old('district', $donor->district) }}" required
                        class="w-full rounded-lg border-gray-300 focus:border-blood-500 focus:ring-blood-500">
                </div>
            </div>

            <button type="submit" class="btn-primary w-full text-center">Save Changes</button>
        </form>
    </div>

</div>
@endsection