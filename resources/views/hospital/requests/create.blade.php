@extends('layouts.app')

@section('title', 'New Blood Request')

@section('nav-links')
    <a href="{{ route('hospital.dashboard') }}" class="text-red-100 hover:text-white text-sm">← Dashboard</a>
@endsection

@section('nav-logout')
    <form method="POST" action="{{ route('hospital.logout') }}">
        @csrf
        <button type="submit" class="text-red-100 hover:text-white text-sm">Logout</button>
    </form>
@endsection

@section('content')

<div class="max-w-xl mx-auto">
    <div class="card">
        <h1 class="text-xl font-bold text-gray-900 mb-6">🚨 Create Emergency Blood Request</h1>

        <form method="POST" action="{{ route('hospital.requests.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Blood Type Needed</label>
                <select name="blood_type" class="w-full rounded-lg border-gray-300 focus:border-blood-500 focus:ring-blood-500">
                    <option value="">Select blood type</option>
                    @foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $type)
                        <option value="{{ $type }}" {{ old('blood_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Units Needed</label>
                <input type="number" name="units_needed" min="1" max="20"
                    value="{{ old('units_needed', 1) }}"
                    class="w-full rounded-lg border-gray-300 focus:border-blood-500 focus:ring-blood-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Urgency Level</label>
                <select name="urgency" class="w-full rounded-lg border-gray-300 focus:border-blood-500 focus:ring-blood-500">
                    <option value="critical" {{ old('urgency') == 'critical' ? 'selected' : '' }}>🔴 Critical — expires in 6 hours</option>
                    <option value="urgent"   {{ old('urgency') == 'urgent'   ? 'selected' : '' }}>🟠 Urgent — expires in 24 hours</option>
                    <option value="standard" {{ old('urgency') == 'standard' ? 'selected' : '' }}>🟡 Standard — expires in 72 hours</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contact Person</label>
                <input type="text" name="contact_person" value="{{ old('contact_person') }}"
                    class="w-full rounded-lg border-gray-300 focus:border-blood-500 focus:ring-blood-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contact Phone</label>
                <input type="text" name="contact_phone" value="{{ old('contact_phone') }}"
                    class="w-full rounded-lg border-gray-300 focus:border-blood-500 focus:ring-blood-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optional)</label>
                <textarea name="notes" rows="3"
                    class="w-full rounded-lg border-gray-300 focus:border-blood-500 focus:ring-blood-500">{{ old('notes') }}</textarea>
            </div>

            <button type="submit" class="btn-primary w-full text-center">
                Create Request
            </button>
        </form>
    </div>
</div>

@endsection