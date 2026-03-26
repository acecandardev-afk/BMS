@extends('errors.minimal')

@section('title', 'Temporarily unavailable')

@section('content')
    <h1 class="h5 fw-bold mb-2" style="color: var(--bc-text);">We’re doing a quick update</h1>
    <p class="small text-muted mb-0">The system will be available again shortly. Thank you for your patience.</p>
    <a href="{{ route('home') }}" class="btn btn-bc-primary rounded-pill mt-4 w-100">Try again</a>
@endsection
