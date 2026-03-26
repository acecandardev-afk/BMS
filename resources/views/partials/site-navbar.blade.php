{{-- Shared landing-style navigation: home, public pages (events), and guest auth --}}
@php
    $onHome = request()->routeIs('home');
    $onEvents = request()->routeIs('events.*');
@endphp
<header class="bc-landing-navbar">
    <div class="container-fluid px-3 px-sm-4 bc-site-nav-container">
        <nav class="navbar navbar-expand-lg align-items-center px-0 py-0">
            <a href="{{ route('home') }}" class="bc-landing-brand me-2 me-lg-3 mb-0">
                <span class="bc-landing-brand-mark" aria-hidden="true"><x-barangay-mark class="text-white" :size="22" /></span>
                <span class="bc-landing-brand-text">
                    <strong>Barangay Cantupa</strong>
                    <span>La Libertad · Negros Oriental</span>
                </span>
            </a>
            <button class="navbar-toggler bc-nav-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#bcSiteNav" aria-controls="bcSiteNav" aria-expanded="false" aria-label="Open menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="bcSiteNav">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1 py-2 py-lg-0 bc-site-nav-list">
                    <li class="nav-item">
                        <a class="nav-link bc-site-nav-link @if($onHome) active @endif" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link bc-site-nav-link" href="{{ route('home') }}#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link bc-site-nav-link" href="{{ route('home') }}#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link bc-site-nav-link @if($onEvents) active @endif" href="{{ route('events.index') }}">Announcements</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link bc-site-nav-link" href="{{ route('home') }}#contact">Contact</a>
                    </li>
                </ul>
                <div class="d-flex flex-wrap align-items-center gap-2 ms-lg-3 pb-3 pb-lg-0 pt-1 pt-lg-0 border-top border-lg-0 bc-site-nav-actions">
                    <button type="button"
                            class="bc-theme-btn"
                            @click.prevent="window.toggleTheme && window.toggleTheme()"
                            :title="theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode'"
                            aria-label="Toggle color theme">
                        <span x-show="theme === 'light'" x-cloak>
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                        </span>
                        <span x-show="theme === 'dark'" x-cloak>
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364l-1.414 1.414M8.05 17.95l-1.414 1.414M18.364 18.364l-1.414-1.414M8.05 6.05L6.636 4.636M12 7a5 5 0 100 10 5 5 0 000-10z"/></svg>
                        </span>
                    </button>
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-bc-primary btn-sm rounded-pill px-4 fw-semibold">Dashboard</a>
                    @else
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-semibold">Register</a>
                        @endif
                        <a href="{{ route('login') }}" class="btn btn-bc-primary btn-sm rounded-pill px-4 fw-semibold shadow-sm">Sign in</a>
                    @endauth
                </div>
            </div>
        </nav>
    </div>
</header>
