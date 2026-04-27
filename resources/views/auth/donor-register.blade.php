<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wareed — Register</title>
    @vite(['resources/css/app.css'])
</head>
<body class="h-full flex items-center justify-center py-10">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <span class="text-5xl">🩸</span>
            <h1 class="text-2xl font-bold text-gray-900 mt-2">Wareed</h1>
            <p class="text-gray-500 text-sm">Create your donor account</p>
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

            <form method="POST" action="/register/donor" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full rounded-lg border-gray-300 focus:border-blood-500 focus:ring-blood-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
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

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Blood Type</label>
                        <select name="blood_type" required
                            class="w-full rounded-lg border-gray-300 focus:border-blood-500 focus:ring-blood-500">
                            <option value="">Select</option>
                            @foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $type)
                                <option value="{{ $type }}" {{ old('blood_type') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-blood-500 focus:ring-blood-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                        <input type="text" name="city" value="{{ old('city') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-blood-500 focus:ring-blood-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">District</label>
                        <input type="text" name="district" value="{{ old('district') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-blood-500 focus:ring-blood-500">
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full text-center">
                    Create Account
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-4">
                Already registered? <a href="/login" class="text-blood-500 font-medium">Login</a>
            </p>
        </div>
    </div>
</body>
</html>