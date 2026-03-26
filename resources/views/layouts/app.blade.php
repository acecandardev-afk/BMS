<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#0f5c4a">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <title>{{ config('app.name', 'Barangay Cantupa') }} — @yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bc-app-body" x-data="layoutShell()" x-init="initLayout()">

    <div class="bc-sidebar-overlay" :class="{ show: sidebarOpen }" @click="sidebarOpen = false" x-show="sidebarOpen" x-transition></div>

    <aside class="bc-sidebar bc-sidebar--fixed" :class="{ show: sidebarOpen }" aria-label="Main navigation">
        <div class="d-flex flex-column h-100">
            <div class="bc-sidebar__brand p-3 p-lg-4 border-bottom border-secondary border-opacity-25">
                <a href="{{ route('dashboard') }}" class="text-decoration-none d-flex align-items-center gap-3">
                    <div class="bc-sidebar__logo rounded-3 d-flex align-items-center justify-content-center flex-shrink-0">
                        <x-barangay-mark class="text-white" :size="24" />
                    </div>
                    <div class="min-w-0">
                        <p class="fw-semibold mb-0 small text-white text-truncate">Barangay Cantupa</p>
                        <p class="mb-0 tiny text-white-50">La Libertad, Neg. Or.</p>
                    </div>
                </a>
            </div>

            <nav class="bc-sidebar__nav flex-grow-1 overflow-auto p-3">
                <a href="{{ route('dashboard') }}" class="bc-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('events.index') }}" class="bc-nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    <span>Events</span>
                </a>

                <a href="{{ route('messages.index') }}" class="bc-nav-link {{ request()->routeIs('messages.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <span class="flex-grow-1">Messages</span>
                    <span class="badge rounded-pill bc-chat-nav-badge" x-show="chatUnread > 0" x-cloak x-text="chatUnread > 99 ? '99+' : chatUnread"></span>
                </a>

                @can('manage-residents')
                <a href="{{ route('residents.index') }}" class="bc-nav-link {{ request()->routeIs('residents.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Residents</span>
                </a>
                <a href="{{ route('households.index') }}" class="bc-nav-link {{ request()->routeIs('households.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9.75L12 3l9 6.75V21a.75.75 0 01-.75.75H15a.75.75 0 01-.75-.75v-5.25h-4.5V21a.75.75 0 01-.75.75H3.75A.75.75 0 013 21V9.75z"/></svg>
                    <span>Households</span>
                </a>
                @endcan

                @can('manage-certificates')
                <a href="{{ route('certificate-requests.index') }}" class="bc-nav-link {{ request()->routeIs('certificate-requests.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Certificates</span>
                </a>
                @endcan

                @can('approve-certificates')
                @cannot('manage-certificates')
                <a href="{{ route('certificate-requests.index') }}" class="bc-nav-link {{ request()->routeIs('certificate-requests.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Approvals</span>
                </a>
                @endcannot
                @endcan

                @auth
                @if(auth()->user()->isResident())
                <a href="{{ route('my.requests') }}" class="bc-nav-link {{ request()->routeIs('my.requests*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>My requests</span>
                </a>
                <a href="{{ route('my.profile') }}" class="bc-nav-link {{ request()->routeIs('my.profile*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>My profile</span>
                </a>
                @endif
                @endauth

                @can('manage-blotter')
                <a href="{{ route('blotter.index') }}" class="bc-nav-link {{ request()->routeIs('blotter.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                    <span>Blotter</span>
                </a>
                @endcan

                <a href="{{ route('legislation.index') }}" class="bc-nav-link {{ request()->routeIs('legislation.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                    <span>Legislation</span>
                </a>

                @can('view-reports')
                <a href="{{ route('reports.index') }}" class="bc-nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Reports</span>
                </a>
                @endcan

                @can('manage-users')
                <a href="{{ route('users.index') }}" class="bc-nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>Users</span>
                </a>
                <a href="{{ route('activity-logs.index') }}" class="bc-nav-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>Audit logs</span>
                </a>
                @endcan
            </nav>

            <div class="bc-sidebar__meta p-3 border-top border-secondary border-opacity-25 d-none d-lg-block">
                <p class="tiny text-white-50 mb-0 px-2">Barangay Management System</p>
            </div>
        </div>
    </aside>

    <div class="bc-main-content bc-main-shell d-flex flex-column min-vh-100">
        <header class="bc-topbar bc-topbar--app sticky-top">
            <div class="bc-topbar__row d-flex align-items-center justify-content-between gap-3 px-3 px-sm-4 px-lg-5">
                <div class="d-flex align-items-center gap-2 min-w-0 flex-grow-1">
                    <button type="button" @click="sidebarOpen = !sidebarOpen" class="bc-icon-btn d-lg-none flex-shrink-0" aria-label="Open navigation menu">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div class="min-w-0">
                        <h1 class="bc-topbar__title mb-0">@yield('title', 'Dashboard')</h1>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-1 gap-sm-2 flex-shrink-0">
                    <time class="bc-topbar__date small text-muted d-none d-md-inline" datetime="{{ now()->toIso8601String() }}">{{ now()->format('M j, Y') }}</time>

                    <button type="button"
                            class="bc-theme-btn"
                            x-data="{ theme: document.documentElement.getAttribute('data-theme') || 'light' }"
                            x-init="window.addEventListener('bc-theme-changed', e => theme = e.detail)"
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

                    <div class="dropdown">
                        <button class="bc-user-trigger d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false" aria-label="Account menu">
                            <span class="bc-user-trigger__avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                            <span class="bc-user-trigger__name d-none d-sm-inline text-truncate">{{ auth()->user()->name }}</span>
                            <svg class="bc-user-trigger__chevron d-none d-sm-block flex-shrink-0" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow bc-dropdown-menu py-2">
                            <li class="px-3 pb-2">
                                <p class="mb-0 fw-semibold small text-truncate" style="max-width: 14rem;">{{ auth()->user()->name }}</p>
                                <p class="mb-0 tiny text-muted text-capitalize">{{ auth()->user()->role }}</p>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            @if(auth()->user()->isResident())
                            <li><a class="dropdown-item small py-2" href="{{ route('my.profile') }}">My profile</a></li>
                            @endif
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="mb-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item small py-2 text-danger">Log out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>

        <div class="bc-main__inner flex-grow-1 px-3 px-sm-4 px-lg-5 pt-3 pt-md-4 pb-5">
            @if(session('success'))
            <div class="alert bc-alert-success alert-dismissible fade show d-flex align-items-center mb-3" role="alert">
                <svg class="me-2 flex-shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="small">{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Dismiss"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert bc-alert-danger alert-dismissible fade show d-flex align-items-center mb-3" role="alert">
                <svg class="me-2 flex-shrink-0" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span class="small">{{ session('error') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Dismiss"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert bc-alert-danger mb-3 d-flex align-items-start gap-2" role="alert">
                <svg class="flex-shrink-0 mt-1" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                <div class="small mb-0">
                    <p class="fw-semibold mb-1 mb-md-2">Please check your information</p>
                    <p class="mb-0 text-muted">Some fields need attention. Review the highlighted items below and try again.</p>
                </div>
            </div>
            @endif

            <main>
                @yield('content')
            </main>
        </div>

        <footer class="bc-site-footer py-3 px-3 text-center small border-top">
            &copy; {{ now()->year }} Barangay Cantupa, La Libertad, Negros Oriental.
        </footer>
    </div>

    @include('partials.confirm-modal')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
