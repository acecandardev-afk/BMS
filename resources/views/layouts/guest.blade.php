<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f5c4a">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <title>{{ config('app.name', 'Barangay Cantupa') }} — @yield('title', 'Login')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bc-landing d-flex flex-column min-vh-100"
      x-data="{ theme: document.documentElement.getAttribute('data-theme') || 'light' }"
      x-init="window.addEventListener('bc-theme-changed', e => { theme = e.detail })">

    @if (Route::has('login'))
        @include('partials.site-navbar')
    @endif

    <div class="flex-grow-1 d-flex flex-column justify-content-center py-4 py-md-5 px-3 bc-guest-page-main">
        <div class="bc-guest-shell mx-auto w-100">
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-4 bc-guest-hero-mark shadow-lg mb-3">
                    <x-barangay-mark class="text-white" :size="36" />
                </div>
                <h1 class="h4 fw-bold mb-1 bc-guest-brand-title">Barangay Cantupa</h1>
                <p class="small mb-0 bc-guest-brand-muted">La Libertad, Negros Oriental</p>
                <p class="small bc-guest-brand-muted mb-0">Barangay Management System</p>
            </div>

            <div class="card bc-login-card border-0 p-4 p-md-5 shadow-lg">
                @yield('content')
            </div>

            <p class="text-center small mt-4 mb-0 px-2" style="color: var(--bc-text-muted);">
                &copy; {{ now()->year }} Barangay Cantupa
            </p>
        </div>
    </div>

    @include('partials.site-footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
