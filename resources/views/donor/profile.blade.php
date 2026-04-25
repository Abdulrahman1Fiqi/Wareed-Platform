<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wareed — My Profile</title>
</head>
<body>
    <h1>My Profile</h1>
    <a href="{{ route('donor.dashboard') }}">← Dashboard</a>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <p>Blood Type: {{ $donor->blood_type }}</p>
    <p>Donation Count: {{ $donor->donation_count }}</p>
    <p>Last Donation: {{ $donor->last_donation_date ? $donor->last_donation_date->format('d M Y') : 'Never' }}</p>

    <form method="POST" action="{{ route('donor.profile.update') }}">
        @csrf
        @method('PUT')
        <input type="text" name="name" value="{{ old('name', $donor->name) }}" placeholder="Full Name">
        <input type="text" name="phone" value="{{ old('phone', $donor->phone) }}" placeholder="Phone">
        <input type="text" name="city" value="{{ old('city', $donor->city) }}" placeholder="City">
        <input type="text" name="district" value="{{ old('district', $donor->district) }}" placeholder="District">
        <button type="submit">Save Changes</button>
    </form>
</body>
</html>