@extends(auth()->check() ? 'layouts.app' : 'layouts.public')

@section('title', $event->title)

@section('content')

<div class="container py-2 py-md-3" style="max-width: 720px;">
    <div class="mb-3">
        <a href="{{ route('events.index') }}" class="bc-back-link small fw-semibold d-inline-flex align-items-center gap-1 text-decoration-none">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            All updates
        </a>
    </div>

    <article class="bc-event-card">
        <div class="bc-event-card-inner p-4 p-md-4">
            <div class="bc-event-meta mb-2">
                <time datetime="{{ $event->created_at->toIso8601String() }}">{{ $event->created_at->format('l, F j, Y · g:i A') }}</time>
                @if($event->author)
                    <span class="text-muted">·</span>
                    <span>{{ $event->author->name }}</span>
                @endif
            </div>
            <h1 class="h4 fw-bold mb-3" style="color: var(--bc-text); letter-spacing: -0.02em;">{{ $event->title }}</h1>
            <div class="bc-event-body" style="color: var(--bc-text-muted);">{{ $event->body }}</div>

            @auth
                @if(auth()->user()->isAdmin())
                    <div class="bc-event-actions">
                        <a href="{{ route('events.edit', $event) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                        <form method="POST" action="{{ route('events.destroy', $event) }}" class="d-inline" data-bc-confirm="Remove this public update?">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>
    </article>
</div>

@endsection
