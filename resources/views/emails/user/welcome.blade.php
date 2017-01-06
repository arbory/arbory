<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Welcome</h2>
<p><b>Account:</b> {{ $email }}</p>
<p>To activate your account, go to <a href="{{ route('auth.activation.attempt', urlencode($code)) }}">{!! route('auth.activation.attempt', urlencode($code)) !!}</a>
</p>
</body>
</html>
