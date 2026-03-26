<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Notice') — {{ config('app.name', 'Barangay Cantupa') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bc-guest-bg min-vh-100 d-flex align-items-center justify-content-center p-4">
    <div class="card bc-login-card border-0 p-4 p-md-5 text-center" style="max-width: 420px; width: 100%;">
        @yield('content')
    </div>
</body>
</html>
