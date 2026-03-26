<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#0f5c4a">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <meta name="description" content="Barangay Cantupa — digital services, certificates, records, and community updates for La Libertad, Negros Oriental.">
    <title>{{ config('app.name', 'Barangay Cantupa') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bc-landing d-flex flex-column"
      x-data="{ theme: document.documentElement.getAttribute('data-theme') || 'light' }"
      x-init="window.addEventListener('bc-theme-changed', e => { theme = e.detail })">

    @if (Route::has('login'))
        @include('partials.site-navbar')
    @endif

    <main class="flex-grow-1">
        <section class="bc-landing-hero">
            <div class="container-fluid px-3 px-sm-4" style="max-width: 1120px; margin: 0 auto;">
                <div class="row align-items-center g-4 g-lg-5">
                    <div class="col-lg-6 order-lg-1">
                        <p class="bc-landing-kicker mb-0">Barangay Management System</p>
                        <h1 class="bc-landing-headline">
                            Digital services for <em>Barangay Cantupa</em>
                        </h1>
                        <p class="bc-landing-sub">
                            Request certificates, stay informed with public updates, and connect with the barangay office—built for residents, staff, and accountable local governance.
                        </p>
                        <div class="bc-landing-hero-cta">
                            @auth
                                <a href="{{ route('dashboard') }}" class="btn btn-bc-primary rounded-pill">Open dashboard</a>
                                <a href="{{ route('events.index') }}" class="btn btn-outline-secondary rounded-pill">Community updates</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-bc-primary rounded-pill">Sign in</a>
                                @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-outline-secondary rounded-pill">Create resident account</a>
                                @endif
                                <a href="{{ route('events.index') }}" class="btn btn-link text-decoration-none px-2 fw-semibold" style="color: var(--bc-link);">View public updates →</a>
                            @endauth
                        </div>
                        <p class="bc-landing-hero-note mb-0">
                            @guest
                                Official announcements are on <a href="{{ route('events.index') }}" class="bc-link">Updates</a>.
                                Staff and officials sign in to manage records and requests.
                            @else
                                You are signed in. Use the dashboard for your role-based tools.
                            @endguest
                        </p>
                    </div>
                    <div class="col-lg-6 order-lg-2">
                        <div class="bc-landing-preview" role="img" aria-label="Illustration of the barangay management dashboard">
                            <div class="bc-landing-preview__chrome">
                                <span></span><span></span><span></span>
                                <span class="bc-landing-preview__url">Barangay Cantupa · BMS</span>
                            </div>
                            <div class="bc-landing-preview__body">
                                <div class="bc-landing-preview__sidebar" aria-hidden="true"></div>
                                <div class="bc-landing-preview__main">
                                    <div class="bc-landing-preview__stat" aria-hidden="true"></div>
                                    <div class="bc-landing-preview__bars" aria-hidden="true">
                                        <span></span><span></span><span></span><span></span><span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="services" class="py-5 border-top border-bottom" style="border-color: var(--bc-border) !important; background: color-mix(in srgb, var(--bc-bg-card) 40%, transparent);">
            <div class="container-fluid px-3 px-sm-4" style="max-width: 1120px; margin: 0 auto;">
                <p class="bc-landing-section-title">What we offer</p>
                <h2 class="bc-landing-section-head">Services in one place</h2>
                <div class="row g-4">
                    <div class="col-md-6 col-xl-3">
                        <article class="bc-landing-feature h-100">
                            <div class="bc-landing-feature__icon">
                                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <h3>Certificates</h3>
                            <p>Request barangay clearance, residency, indigency, and more—with status tracking online.</p>
                        </article>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <article class="bc-landing-feature h-100">
                            <div class="bc-landing-feature__icon">
                                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <h3>Records &amp; households</h3>
                            <p>Structured resident and household data for staff—secure, searchable, and up to date.</p>
                        </article>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <article class="bc-landing-feature h-100">
                            <div class="bc-landing-feature__icon">
                                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                            </div>
                            <h3>Community updates</h3>
                            <p>Public news and announcements from the barangay—no account required to read.</p>
                        </article>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <article class="bc-landing-feature h-100">
                            <div class="bc-landing-feature__icon">
                                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            </div>
                            <h3>Messages</h3>
                            <p>Residents can chat with office staff after signing in—quick questions without a trip to the hall.</p>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <section id="about" class="py-5 border-bottom" style="border-color: var(--bc-border) !important;">
            <div class="container-fluid px-3 px-sm-4" style="max-width: 1120px; margin: 0 auto;">
                <div class="row align-items-center g-4 g-lg-5">
                    <div class="col-lg-6">
                        <p class="bc-landing-section-title text-lg-start">Our barangay</p>
                        <h2 class="bc-landing-section-head text-lg-start mb-3">Serving Cantupa with transparency</h2>
                        <p class="small text-muted mb-3 mb-lg-4" style="max-width: 36rem;">
                            Barangay Cantupa supports residents of La Libertad through fair services, accurate records, and open communication.
                            This portal extends our hall online—so you can request documents, read official updates, and reach staff when it matters.
                        </p>
                        <ul class="small text-muted mb-0 ps-3" style="max-width: 36rem;">
                            <li class="mb-2">Official certificates and blotter services handled with care.</li>
                            <li class="mb-2">Public announcements posted here first.</li>
                            <li>Residents with an account can track requests and message the office.</li>
                        </ul>
                    </div>
                    <div class="col-lg-6">
                        <div class="bc-landing-about-visual rounded-4 border p-4 p-md-5 text-center" style="border-color: var(--bc-border) !important; background: var(--bc-bg-card); box-shadow: var(--bc-shadow-md);">
                            <div class="bc-landing-feature__icon mx-auto mb-3">
                                <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <p class="fw-semibold mb-1" style="color: var(--bc-text);">Barangay Cantupa</p>
                            <p class="small text-muted mb-0">Municipality of La Libertad · Negros Oriental</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5">
            <div class="container-fluid px-3 px-sm-4" style="max-width: 720px; margin: 0 auto;">
                <div class="bc-landing-band">
                    <h2>Ready to use the portal?</h2>
                    <p>Residents can register for an account. If you work in the barangay, use the account provided by your administrator.</p>
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        @auth
                            <a href="{{ route('dashboard') }}" class="btn btn-light">Go to dashboard</a>
                            <a href="{{ route('events.index') }}" class="btn btn-outline-light">Latest updates</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-light">Sign in</a>
                            @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-outline-light">Register as resident</a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </section>

        <section id="contact" class="py-5 border-top" style="border-color: var(--bc-border) !important; background: color-mix(in srgb, var(--bc-bg-card) 35%, transparent);">
            <div class="container-fluid px-3 px-sm-4" style="max-width: 1120px; margin: 0 auto;">
                <p class="bc-landing-section-title">Contact</p>
                <h2 class="bc-landing-section-head">Visit or reach the barangay hall</h2>
                <div class="row g-4 justify-content-center">
                    <div class="col-sm-6 col-lg-4">
                        <div class="bc-landing-contact-card h-100 p-4 rounded-4 border text-center" style="border-color: var(--bc-border) !important; background: var(--bc-bg-card);">
                            <span class="bc-landing-contact-card__icon d-inline-flex mb-3" aria-hidden="true">
                                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </span>
                            <h3 class="h6 fw-bold mb-2">Address</h3>
                            <p class="small text-muted mb-0">Barangay Hall, Cantupa<br>La Libertad, Negros Oriental</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="bc-landing-contact-card h-100 p-4 rounded-4 border text-center" style="border-color: var(--bc-border) !important; background: var(--bc-bg-card);">
                            <span class="bc-landing-contact-card__icon d-inline-flex mb-3" aria-hidden="true">
                                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            </span>
                            <h3 class="h6 fw-bold mb-2">Phone</h3>
                            <p class="small text-muted mb-0">Contact your barangay office for the official hotline.</p>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <div class="bc-landing-contact-card h-100 p-4 rounded-4 border text-center" style="border-color: var(--bc-border) !important; background: var(--bc-bg-card);">
                            <span class="bc-landing-contact-card__icon d-inline-flex mb-3" aria-hidden="true">
                                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            <h3 class="h6 fw-bold mb-2">Email</h3>
                            <p class="small text-muted mb-0">Ask at the hall for the official barangay email, if available.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    @include('partials.site-footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
