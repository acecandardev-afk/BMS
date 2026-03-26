@extends('layouts.app')

@section('title', 'Chat with '.$partner->name)

@section('content')

<div class="py-3 py-md-4">
    <div class="mb-3 d-flex flex-wrap align-items-center gap-2">
        <a href="{{ route('messages.index') }}" class="small bc-back-link text-decoration-none d-inline-flex align-items-center gap-1">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            All messages
        </a>
    </div>

    <div class="card bc-card bc-chat-thread-card overflow-hidden"
         x-data="chatThread(@js($partner->id), @js($messagesPayload), @js($lastMessageId))"
         x-init="startPolling()">
        <div class="bc-chat-thread-header p-3 border-bottom d-flex align-items-center gap-3">
            <div class="rounded-circle bc-chat-avatar flex-shrink-0 d-flex align-items-center justify-content-center fw-bold text-white">
                {{ strtoupper(substr($partner->name, 0, 2)) }}
            </div>
            <div class="min-w-0">
                <h2 class="h6 mb-0 text-truncate bc-page-title">{{ $partner->name }}</h2>
                <p class="small mb-0 bc-page-subtitle">{{ ucfirst(str_replace('_', ' ', $partner->role)) }}</p>
            </div>
        </div>

        <div class="bc-chat-messages p-3" id="chat-scroll" x-ref="scrollBox" style="max-height: min(60vh, 520px); overflow-y: auto;">
            <p x-show="messages.length === 0" x-cloak class="small text-center py-4 mb-0" style="color: var(--bc-text-muted);">No messages yet. Say hello below.</p>
            <template x-for="msg in messages" :key="msg.id">
                <div class="mb-3" :class="msg.mine ? 'text-end' : ''">
                    <div class="d-inline-block text-start bc-chat-bubble px-3 py-2 rounded-4 shadow-sm"
                         :class="msg.mine ? 'bc-chat-bubble-mine' : 'bc-chat-bubble-theirs'"
                         style="max-width: min(100%, 85%);">
                        <template x-if="!msg.mine">
                            <p class="small fw-semibold mb-1" style="color: var(--bc-text-muted);" x-text="msg.sender_name"></p>
                        </template>
                        <p class="mb-0 small" style="white-space: pre-wrap; word-break: break-word; color: var(--bc-text);" x-text="msg.body"></p>
                        <p class="small mb-0 mt-1" style="color: var(--bc-text-muted); font-size: 0.7rem;" x-text="msg.time"></p>
                    </div>
                </div>
            </template>
        </div>

        <div class="p-3 border-top bg-opacity-50" style="background: var(--bc-bg);">
            <form method="POST" action="{{ route('messages.store', $partner) }}" class="d-flex flex-column flex-sm-row gap-2">
                @csrf
                <label class="visually-hidden" for="chat-body">Message</label>
                <textarea id="chat-body" name="body" rows="2" class="form-control flex-grow-1"
                          placeholder="Write your message…" required maxlength="5000">{{ old('body') }}</textarea>
                <button type="submit" class="btn btn-bc-primary rounded-pill px-4 align-self-stretch align-self-sm-center">Send</button>
            </form>
        </div>
    </div>
</div>

@endsection
