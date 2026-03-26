@extends('layouts.app')

@section('title', 'Messages')

@section('content')

<div class="py-3 py-md-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div class="min-w-0">
            <h2 class="h5 mb-1 bc-page-title">Messages</h2>
            <p class="small mb-0 bc-page-subtitle">Chat with barangay staff or residents when you need help.</p>
        </div>
        <a href="{{ route('messages.create') }}" class="btn btn-bc-primary btn-sm rounded-pill flex-shrink-0">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New conversation
        </a>
    </div>

    <div class="card bc-card overflow-hidden">
        @forelse($partners as $partner)
            @php
                $last = $lastMessages[$partner->id] ?? null;
                $unread = $unreadCounts[$partner->id] ?? 0;
            @endphp
            <a href="{{ route('messages.show', $partner) }}" class="d-flex align-items-center gap-3 p-3 text-decoration-none bc-chat-row border-bottom">
                <div class="rounded-circle bc-chat-avatar flex-shrink-0 d-flex align-items-center justify-content-center fw-bold text-white">
                    {{ strtoupper(substr($partner->name, 0, 2)) }}
                </div>
                <div class="min-w-0 flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <p class="mb-0 fw-semibold text-truncate" style="color: var(--bc-text);">{{ $partner->name }}</p>
                        @if($last)
                            <span class="small flex-shrink-0" style="color: var(--bc-text-muted);">{{ $last->created_at->diffForHumans() }}</span>
                        @endif
                    </div>
                    <p class="small mb-0 text-truncate" style="color: var(--bc-text-muted);">
                        @if($last)
                            {{ $last->sender_id === auth()->id() ? 'You: ' : '' }}{{ Str::limit($last->body, 72) }}
                        @else
                            Start the conversation
                        @endif
                    </p>
                </div>
                @if($unread > 0)
                    <span class="badge rounded-pill bc-chat-unread-badge flex-shrink-0">{{ $unread > 99 ? '99+' : $unread }}</span>
                @endif
            </a>
        @empty
            <div class="p-5 text-center">
                <div class="bc-chat-empty-icon mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle">
                    <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <p class="fw-medium mb-1" style="color: var(--bc-text);">No conversations yet</p>
                <p class="small mb-4" style="color: var(--bc-text-muted);">Reach out to the barangay office or start a new chat.</p>
                <a href="{{ route('messages.create') }}" class="btn btn-bc-primary btn-sm rounded-pill">Start a conversation</a>
            </div>
        @endforelse
    </div>
</div>

@endsection
