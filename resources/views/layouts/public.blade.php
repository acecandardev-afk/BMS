<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f5c4a">
    <title>{{ config('app.name', 'Barangay Cantupa') }} — @yield('title', 'Barangay updates')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bc-landing d-flex flex-column min-vh-100 bc-public-wrap"
      x-data="{ theme: document.documentElement.getAttribute('data-theme') || 'light' }"
      x-init="window.addEventListener('bc-theme-changed', e => { theme = e.detail })">

    @if (Route::has('login'))
        @include('partials.site-navbar')
    @endif

    <div class="bc-public-main flex-grow-1 w-100">
        <div class="container py-3 py-md-4 mx-auto" style="max-width: 720px;">
            @if(session('success'))
            <div class="alert bc-alert-success alert-dismissible fade show d-flex align-items-center mb-3" role="alert">
                <svg class="me-2 flex-shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="small">{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Dismiss"></button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert bc-alert-danger alert-dismissible fade show d-flex align-items-center mb-3" role="alert">
                <span class="small">{{ session('error') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Dismiss"></button>
            </div>
            @endif
        </div>

        @yield('content')
    </div>

    @include('partials.site-footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
