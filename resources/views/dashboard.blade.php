@extends('layouts.app')

@section('title', 'Dashboard')

@php
    $roleLabel = match (auth()->user()->role) {
        'admin' => 'Administrator',
        'staff' => 'Barangay Staff',
        'signatory' => 'Signatory',
        'resident' => 'Resident',
        default => 'User',
    };
    $greeting = now()->hour < 12 ? 'Good morning' : (now()->hour < 17 ? 'Good afternoon' : 'Good evening');
@endphp

@section('content')

<div class="py-3 py-md-4">

    {{-- Welcome — all roles --}}
    <div class="bc-dash-hero mb-4">
        <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-3">
            <div class="min-w-0">
                <p class="bc-dash-hero-kicker mb-1">{{ $greeting }}</p>
                <h1 class="bc-dash-hero-title text-truncate">{{ auth()->user()->name }}</h1>
                <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
                    <span class="bc-dash-role-badge">{{ $roleLabel }}</span>
                    @if($unreadMessages > 0)
                        <a href="{{ route('messages.index') }}" class="bc-dash-hero-messages">
                            <span class="bc-dash-hero-messages-dot"></span>
                            {{ $unreadMessages }} unread message{{ $unreadMessages === 1 ? '' : 's' }}
                        </a>
                    @endif
                </div>
            </div>
            <div class="bc-dash-hero-aside text-lg-end flex-shrink-0">
                <p class="bc-dash-hero-date mb-0">{{ now()->format('l, F j, Y') }}</p>
                <p class="bc-dash-hero-tagline mb-0">Barangay Cantupa · La Libertad</p>
            </div>
        </div>
    </div>

    <div class="bc-dash-public-banner">
        <div class="d-flex align-items-start gap-3 min-w-0">
            <div class="bc-dash-public-banner-icon">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
            </div>
            <div class="min-w-0 flex-grow-1">
                <h2>Barangay updates</h2>
                <p>Read public announcements and community news—available to everyone, with or without an account.</p>
            </div>
        </div>
        <a href="{{ route('events.index') }}" class="btn btn-bc-primary btn-sm rounded-pill flex-shrink-0 align-self-center">View updates</a>
    </div>

    {{-- ========== ADMIN ========== --}}
    @if(auth()->user()->isAdmin())
    <div class="row g-3 g-lg-4 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stat-card label="Total Residents" :value="$data['total_residents']" color="blue"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'/>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stat-card label="Pending Requests" :value="$data['pending_requests']" color="yellow"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'/>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stat-card label="Approved Requests" :value="$data['approved_requests']" color="green"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'/>
        </div>
        <div class="col-12 col-sm-6 col-xl-3">
            <x-stat-card label="Open Blotter Cases" :value="$data['open_blotter']" color="red"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>'/>
        </div>
    </div>
    @endif

    {{-- ========== STAFF ========== --}}
    @if(auth()->user()->isStaff())
    <div class="row g-3 g-lg-4 mb-4">
        <div class="col-12 col-sm-6 col-lg-4">
            <x-stat-card label="Total Residents" :value="$data['total_residents']" color="blue"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>'/>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <x-stat-card label="Pending Requests" :value="$data['pending_requests']" color="yellow"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'/>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <x-stat-card label="Open Blotter Cases" :value="$data['open_blotter']" color="red"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>'/>
        </div>
    </div>

    <h2 class="bc-dash-section-title">Shortcuts</h2>
    <div class="bc-dash-quick-grid mb-4">
        <a href="{{ route('residents.index') }}" class="bc-dash-quick-link">
            <span class="bc-dash-quick-icon"><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></span>
            <span class="bc-dash-quick-text"><span class="bc-dash-quick-label">Residents</span><span class="bc-dash-quick-hint">Registry</span></span>
        </a>
        <a href="{{ route('residents.create') }}" class="bc-dash-quick-link">
            <span class="bc-dash-quick-icon"><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></span>
            <span class="bc-dash-quick-text"><span class="bc-dash-quick-label">Add resident</span><span class="bc-dash-quick-hint">New entry</span></span>
        </a>
        <a href="{{ route('households.index') }}" class="bc-dash-quick-link">
            <span class="bc-dash-quick-icon"><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9.75L12 3l9 6.75V21a.75.75 0 01-.75.75H15a.75.75 0 01-.75-.75v-5.25h-4.5V21a.75.75 0 01-.75.75H3.75A.75.75 0 013 21V9.75z"/></svg></span>
            <span class="bc-dash-quick-text"><span class="bc-dash-quick-label">Households</span><span class="bc-dash-quick-hint">Household list</span></span>
        </a>
        <a href="{{ route('certificate-requests.index') }}" class="bc-dash-quick-link">
            <span class="bc-dash-quick-icon"><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></span>
            <span class="bc-dash-quick-text"><span class="bc-dash-quick-label">Certificates</span><span class="bc-dash-quick-hint">Process requests</span></span>
        </a>
        <a href="{{ route('blotter.index') }}" class="bc-dash-quick-link">
            <span class="bc-dash-quick-icon"><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg></span>
            <span class="bc-dash-quick-text"><span class="bc-dash-quick-label">Blotter</span><span class="bc-dash-quick-hint">All cases</span></span>
        </a>
        <a href="{{ route('blotter.create') }}" class="bc-dash-quick-link">
            <span class="bc-dash-quick-icon"><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></span>
            <span class="bc-dash-quick-text"><span class="bc-dash-quick-label">File blotter</span><span class="bc-dash-quick-hint">New case</span></span>
        </a>
        <a href="{{ route('reports.index') }}" class="bc-dash-quick-link">
            <span class="bc-dash-quick-icon"><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></span>
            <span class="bc-dash-quick-text"><span class="bc-dash-quick-label">Reports</span><span class="bc-dash-quick-hint">Summaries</span></span>
        </a>
        <a href="{{ route('legislation.index') }}" class="bc-dash-quick-link">
            <span class="bc-dash-quick-icon"><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg></span>
            <span class="bc-dash-quick-text"><span class="bc-dash-quick-label">Legislation</span><span class="bc-dash-quick-hint">Library</span></span>
        </a>
        <a href="{{ route('messages.index') }}" class="bc-dash-quick-link">
            <span class="bc-dash-quick-icon"><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg></span>
            <span class="bc-dash-quick-text"><span class="bc-dash-quick-label">Messages</span><span class="bc-dash-quick-hint">Chat</span></span>
        </a>
    </div>

    @if(($data['pending_requests'] ?? 0) > 0)
    <div class="card bc-card bc-dash-cta mb-0">
        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3">
            <div>
                <p class="bc-dash-cta-title mb-1">Certificate queue needs attention</p>
                <p class="small text-muted mb-0">{{ $data['pending_requests'] }} request(s) are pending review.</p>
            </div>
            <a href="{{ route('certificate-requests.index') }}?status=pending" class="btn btn-bc-primary btn-sm flex-shrink-0">Open queue</a>
        </div>
    </div>
    @endif
    @endif

    {{-- ========== SIGNATORY ========== --}}
    @if(auth()->user()->isSignatory())
    <div class="row g-3 g-lg-4 mb-4">
        <div class="col-12 col-sm-6 col-lg-4">
            <x-stat-card label="Pending Approvals" :value="$data['pending_requests']" color="yellow"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'/>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <x-stat-card label="Approved" :value="$data['approved_requests']" color="green"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'/>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <x-stat-card label="Total Requests" :value="$data['total_requests']" color="blue"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'/>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card bc-card bc-dash-panel bc-dash-cta bc-dash-cta--signatory">
                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                    <div>
                        <h2 class="bc-dash-panel-title mb-2">Certificates awaiting your signature</h2>
                        <p class="small mb-0" style="color: var(--bc-text-muted);">
                            <strong class="bc-dash-cta-accent">{{ $data['pending_requests'] }}</strong> request(s) need approval or rejection.
                        </p>
                    </div>
                    <a href="{{ route('certificate-requests.index') }}?status=pending" class="btn btn-light btn-sm flex-shrink-0 fw-semibold">Review now</a>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <h2 class="bc-dash-section-title">Shortcuts</h2>
            <div class="bc-dash-quick-grid">
                <a href="{{ route('certificate-requests.index') }}" class="bc-dash-quick-link">
                    <span class="bc-dash-quick-icon"><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></span>
                    <span class="bc-dash-quick-text"><span class="bc-dash-quick-label">All requests</span><span class="bc-dash-quick-hint">Full list</span></span>
                </a>
                <a href="{{ route('legislation.index') }}" class="bc-dash-quick-link">
                    <span class="bc-dash-quick-icon"><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg></span>
                    <span class="bc-dash-quick-text"><span class="bc-dash-quick-label">Legislation</span><span class="bc-dash-quick-hint">Browse</span></span>
                </a>
                <a href="{{ route('messages.index') }}" class="bc-dash-quick-link">
                    <span class="bc-dash-quick-icon"><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg></span>
                    <span class="bc-dash-quick-text"><span class="bc-dash-quick-label">Messages</span><span class="bc-dash-quick-hint">Office chat</span></span>
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- ========== RESIDENT ========== --}}
    @if(auth()->user()->isResident())
    <div class="row g-3 g-lg-4 mb-4">
        <div class="col-12 col-sm-6 col-lg-4">
            <x-stat-card label="My Pending" :value="$data['my_pending_requests']" color="yellow"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'/>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <x-stat-card label="Approved" :value="$data['my_approved_requests']" color="green"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'/>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <x-stat-card label="Total Requests" :value="$data['my_total_requests']" color="blue"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'/>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card bc-card bc-dash-panel">
                <h2 class="bc-dash-panel-title mb-2">Your services</h2>
                <p class="small mb-3" style="color: var(--bc-text-muted);">Request certificates, track status, and reach the barangay office.</p>
                <div class="bc-dash-quick-grid bc-dash-quick-grid--dense">
                    <a href="{{ route('my.requests.create') }}" class="bc-dash-quick-link">
                        <span class="bc-dash-quick-icon"><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg></span>
                        <span class="bc-dash-quick-text"><span class="bc-dash-quick-label">New request</span><span class="bc-dash-quick-hint">Apply for a certificate</span></span>
                    </a>
                    <a href="{{ route('my.requests') }}" class="bc-dash-quick-link">
                        <span class="bc-dash-quick-icon"><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></span>
                        <span class="bc-dash-quick-text"><span class="bc-dash-quick-label">My requests</span><span class="bc-dash-quick-hint">Status & history</span></span>
                    </a>
                    <a href="{{ route('my.profile') }}" class="bc-dash-quick-link">
                        <span class="bc-dash-quick-icon"><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></span>
                        <span class="bc-dash-quick-text"><span class="bc-dash-quick-label">My profile</span><span class="bc-dash-quick-hint">Contact & address</span></span>
                    </a>
                    <a href="{{ route('legislation.index') }}" class="bc-dash-quick-link">
                        <span class="bc-dash-quick-icon"><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg></span>
                        <span class="bc-dash-quick-text"><span class="bc-dash-quick-label">Legislation</span><span class="bc-dash-quick-hint">Ordinances & resolutions</span></span>
                    </a>
                    <a href="{{ route('messages.index') }}" class="bc-dash-quick-link">
                        <span class="bc-dash-quick-icon"><svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg></span>
                        <span class="bc-dash-quick-text"><span class="bc-dash-quick-label">Messages</span><span class="bc-dash-quick-hint">Contact the office</span></span>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card bc-card bc-dash-panel bc-dash-tip">
                <h2 class="bc-dash-panel-title mb-2">Tips</h2>
                <ul class="small mb-0 ps-3" style="color: var(--bc-text-muted);">
                    <li class="mb-2">Submit certificate requests with clear purpose details to avoid delays.</li>
                    <li class="mb-2">Check <strong>My requests</strong> for updates after staff review.</li>
                    <li>Use <strong>Messages</strong> for quick questions to barangay staff.</li>
                </ul>
            </div>
        </div>
    </div>
    @endif

</div>

@endsection
