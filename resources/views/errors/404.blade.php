@extends('errors.minimal')

@section('title', 'Page not found')

@section('content')
    <h1 class="h5 fw-bold mb-2" style="color: var(--bc-text);">We couldn’t find that page</h1>
    <p class="small text-muted mb-0">The link may be outdated or the page may have been moved.</p>
    <a href="{{ route('home') }}" class="btn btn-bc-primary rounded-pill mt-4 w-100">Go to home</a>
@endsection
