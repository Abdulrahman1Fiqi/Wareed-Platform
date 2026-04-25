<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Approved</title>
</head>
<body>
    <h1>Congratulations, {{ $hospital->name }}</h1>
    <p>Your Wareed account has been approved.</p>
    <p>You can now log in and create emergency blood requests.</p>
    <a href="{{ url('/hospital/login') }}">Login to Wareed</a>
</body>
</html>