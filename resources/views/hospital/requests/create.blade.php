<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wareed — New Blood Request</title>
</head>
<body>
    <h1>Create Emergency Blood Request</h1>

    <a href="{{ route('hospital.dashboard') }}">← Back</a>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('hospital.requests.store') }}">
        @csrf

        <label>Blood Type</label>
        <select name="blood_type">
            <option value="">Select</option>
            @foreach (['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $type)
                <option value="{{ $type }}" {{ old('blood_type') == $type ? 'selected' : '' }}>
                    {{ $type }}
                </option>
            @endforeach
        </select>

        <label>Units Needed</label>
        <input type="number" name="units_needed" min="1" max="20" value="{{ old('units_needed', 1) }}">

        <label>Urgency</label>
        <select name="urgency">
            <option value="critical" {{ old('urgency') == 'critical' ? 'selected' : '' }}>🔴 Critical (6 hours)</option>
            <option value="urgent"   {{ old('urgency') == 'urgent'   ? 'selected' : '' }}>🟠 Urgent (24 hours)</option>
            <option value="standard" {{ old('urgency') == 'standard' ? 'selected' : '' }}>🟡 Standard (72 hours)</option>
        </select>

        <label>Contact Person</label>
        <input type="text" name="contact_person" value="{{ old('contact_person') }}">

        <label>Contact Phone</label>
        <input type="text" name="contact_phone" value="{{ old('contact_phone') }}">

        <label>Notes (optional)</label>
        <textarea name="notes">{{ old('notes') }}</textarea>

        <button type="submit">Create Request</button>
    </form>
</body>
</html>