@extends(auth()->check() ? 'layouts.app' : 'layouts.public')

@section('title', 'Barangay events & updates')

@section('content')

<div class="container py-1 py-md-2" style="max-width: 720px;">

    <div class="bc-events-hero mb-4">
        <div class="d-flex flex-column flex-sm-row align-items-start justify-content-between gap-3">
            <div class="min-w-0">
                <h1>Barangay updates</h1>
                <p>Official announcements and news for residents and the public.</p>
            </div>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('events.create') }}" class="btn btn-light btn-sm fw-semibold rounded-pill flex-shrink-0 shadow-sm">
                        Post update
                    </a>
                @endif
            @endauth
        </div>
    </div>

    @forelse($events as $event)
        <article class="bc-event-card mb-3">
            <div class="bc-event-card-inner">
                <div class="bc-event-meta">
                    <time datetime="{{ $event->created_at->toIso8601String() }}">{{ $event->created_at->format('M j, Y · g:i A') }}</time>
                    @if($event->author)
                        <span class="text-muted">·</span>
                        <span>{{ $event->author->name }}</span>
                    @endif
                </div>
                <h2 class="bc-event-title mb-2">
                    <a href="{{ route('events.show', $event) }}" class="text-decoration-none" style="color: inherit;">{{ $event->title }}</a>
                </h2>
                @php $long = mb_strlen($event->body) > 300; @endphp
                <div class="bc-event-body {{ $long ? 'bc-event-body--excerpt' : '' }}">{{ $long ? \Illuminate\Support\Str::limit($event->body, 300) : $event->body }}</div>
                @if($long)
                    <p class="mb-0 mt-2">
                        <a href="{{ route('events.show', $event) }}" class="bc-link small">Read full update →</a>
                    </p>
                @endif
                @auth
                    @if(auth()->user()->isAdmin())
                        <div class="bc-event-actions position-relative">
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
    @empty
        <div class="bc-event-card">
            <div class="bc-event-card-inner text-center py-5 px-3">
                <div class="mb-3 opacity-75" style="color: var(--bc-primary);">
                    <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="mx-auto d-block"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                </div>
                <p class="small text-muted mb-0">No updates have been posted yet. Check back soon.</p>
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('events.create') }}" class="btn btn-bc-primary btn-sm rounded-pill mt-3">Post the first update</a>
                    @endif
                @endauth
            </div>
        </div>
    @endforelse

    @if($events->hasPages())
        <div class="d-flex justify-content-center pt-2">
            {{ $events->links() }}
        </div>
    @endif
</div>

@endsection
