<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wareed — Hospital Registration</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center py-10">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <span class="text-5xl">🏥</span>
            <h1 class="text-2xl font-bold text-gray-900 mt-2">Register Your Hospital</h1>
            <p class="text-gray-500 text-sm">Pending admin approval after registration</p>
        </div>

        <div class="card">
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm mb-4">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="/register/hospital" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hospital Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full rounded-lg border-gray-300 focus:border-blood-500 focus:ring-blood-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Official Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full rounded-lg border-gray-300 focus:border-blood-500 focus:ring-blood-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" required
                            class="w-full rounded-lg border-gray-300 focus:border-blood-500 focus:ring-blood-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full rounded-lg border-gray-300 focus:border-blood-500 focus:ring-blood-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required
                        class="w-full rounded-lg border-gray-300 focus:border-blood-500 focus:ring-blood-500">
                </div>

                <x-city-district-select
                    :selectedCity="old('city', '')"
                    :selectedDistrict="old('district', '')"
                />

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">License Number</label>
                    <input type="text" name="license_number" value="{{ old('license_number') }}" required
                        class="w-full rounded-lg border-gray-300 focus:border-blood-500 focus:ring-blood-500">
                </div>

                <button type="submit" class="btn-primary w-full text-center">Submit Registration</button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-4">
                Already registered? <a href="/hospital/login" class="text-blood-500 font-medium">Login</a>
            </p>
        </div>
    </div>
</body>
</html>