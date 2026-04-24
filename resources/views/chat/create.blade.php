@extends('layouts.app')

@section('title', 'New conversation')

@section('content')

<div class="py-3 py-md-4">
    <div class="mb-4">
        <a href="{{ route('messages.index') }}" class="small bc-back-link text-decoration-none d-inline-flex align-items-center gap-1">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to messages
        </a>
        <h2 class="h5 mt-2 mb-1 bc-page-title">Who would you like to message?</h2>
        <p class="small mb-0 bc-page-subtitle">
            @if(auth()->user()->isOfficeUser())
                You can message any active user in the system.
            @else
                You can message barangay administrators, staff, or signatories.
            @endif
        </p>
    </div>

    <form method="GET" action="{{ route('messages.create') }}" class="bc-filter-bar mb-3" role="search" aria-label="Search users to message">
        <div class="bc-filter-bar-inner">
            <div class="bc-filter-field-search bc-filter-field-search--wide">
                <label for="msg-user-search" class="visually-hidden">Search by name or email</label>
                <input type="search" name="search" id="msg-user-search" value="{{ request('search') }}"
                       class="form-control form-control-sm" placeholder="Name or email…" autocomplete="off">
            </div>
            <div class="bc-filter-actions">
                <button type="submit" class="btn btn-bc-primary btn-sm">Search</button>
            </div>
        </div>
    </form>

    <div class="card bc-card overflow-hidden">
        <div class="list-group list-group-flush bc-chat-user-list">
            @forelse($users as $u)
                <a href="{{ route('messages.show', $u) }}"
                   class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 bc-chat-user-item">
                    <div class="rounded-circle bc-chat-avatar-sm flex-shrink-0 d-flex align-items-center justify-content-center fw-bold text-white small">
                        {{ strtoupper(substr($u->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="mb-0 fw-semibold text-truncate" style="color: var(--bc-text);">{{ $u->name }}</p>
                        <p class="small mb-0 text-truncate" style="color: var(--bc-text-muted);">{{ ucfirst(str_replace('_', ' ', $u->role)) }}</p>
                    </div>
                </a>
            @empty
                <p class="small text-muted mb-0 py-3">
                    @if(request()->filled('search'))
                        No matches found. Try a different search.
                    @else
                        No one is available to message right now. Please try again later.
                    @endif
                </p>
            @endforelse
        </div>
    </div>
</div>

@endsection
