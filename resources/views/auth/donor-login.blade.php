<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wareed — Login</title>
    @vite(['resources/css/app.css'])
</head>
<body class="h-full flex items-center justify-center">
    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <span class="text-5xl">🩸</span>
            <h1 class="text-2xl font-bold text-gray-900 mt-2">Wareed</h1>
            <p class="text-gray-500 text-sm">Blood Donation Network</p>
        </div>

        <div class="card">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Donor Login</h2>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm mb-4">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="/login" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full rounded-lg border-gray-300 focus:border-blood-500 focus:ring-blood-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" required
                        class="w-full rounded-lg border-gray-300 focus:border-blood-500 focus:ring-blood-500">
                </div>
                <button type="submit" class="btn-primary w-full text-center">Login</button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-4">
                No account? <a href="/register/donor" class="text-blood-500 font-medium">Register as Donor</a>
            </p>
            <p class="text-center text-sm text-gray-500 mt-1">
                Hospital? <a href="/hospital/login" class="text-blood-500 font-medium">Hospital Login</a>
            </p>
            <p class="text-center text-sm text-gray-500 mt-1">
                Admin? <a href="/admin/login" class="text-blood-500 font-medium">Admin Login</a>
            </p>
        </div>
    </div>
</body>
</html>