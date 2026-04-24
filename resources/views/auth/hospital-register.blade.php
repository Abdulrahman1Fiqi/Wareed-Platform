<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wareed — Hospital Register</title>
</head>
<body>
    <h1>Hospital Registration</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="/register/hospital">
        @csrf
        <input type="text" name="name" placeholder="Hospital Name" value="{{ old('name') }}">
        <input type="email" name="email" placeholder="Official Email" value="{{ old('email') }}">
        <input type="password" name="password" placeholder="Password">
        <input type="password" name="password_confirmation" placeholder="Confirm Password">
        <input type="text" name="phone" placeholder="Phone" value="{{ old('phone') }}">
        <input type="text" name="city" placeholder="City" value="{{ old('city') }}">
        <input type="text" name="district" placeholder="District" value="{{ old('district') }}">
        <input type="text" name="license_number" placeholder="License Number" value="{{ old('license_number') }}">
        <button type="submit">Register</button>
    </form>

    <a href="/hospital/login">Already registered? Login</a>
</body>
</html>