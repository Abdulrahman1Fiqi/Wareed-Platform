<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wareed — Verify Email</title>
    @vite(['resources/css/app.css'])
</head>
<body class="h-full flex items-center justify-center">
    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <span class="text-5xl">📧</span>
            <h1 class="text-2xl font-bold text-gray-900 mt-2">Check your email</h1>
            <p class="text-gray-500 text-sm mt-1">We sent a verification link to your email address</p>
        </div>

        <div class="card text-center space-y-4">
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <p class="text-sm text-gray-500">
                Click the link in your email to activate your account. Check your spam folder if you don't see it.
            </p>

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn-secondary w-full">Resend Verification Email</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-400 hover:text-gray-600">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>