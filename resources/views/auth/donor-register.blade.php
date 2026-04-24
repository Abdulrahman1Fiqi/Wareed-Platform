<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wareed — Donor Register</title>
</head>
<body>
    <h1>Donor Registration</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="/register/donor">
        @csrf
        <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}">
        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}">
        <input type="password" name="password" placeholder="Password">
        <input type="password" name="password_confirmation" placeholder="Confirm Password">
        <select name="blood_type">
            <option value="">Select Blood Type</option>
            @foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $type)
                <option value="{{ $type }}" {{ old('blood_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
            @endforeach
        </select>
        <input type="text" name="phone" placeholder="Phone" value="{{ old('phone') }}">
        <input type="text" name="city" placeholder="City" value="{{ old('city') }}">
        <input type="text" name="district" placeholder="District" value="{{ old('district') }}">
        <button type="submit">Register</button>
    </form>

    <a href="/login">Already have an account? Login</a>
</body>
</html>