<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wareed — Hospital Login</title>
</head>
<body>
    <h1>Hospital Login</h1>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <form method="POST" action="/hospital/login">
        @csrf
        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}">
        <input type="password" name="password" placeholder="Password">
        <button type="submit">Login</button>
    </form>

    <a href="/register/hospital">Register your Hospital</a>
</body>
</html>