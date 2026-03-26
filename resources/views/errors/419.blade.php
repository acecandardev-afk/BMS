@extends('errors.minimal')

@section('title', 'Session expired')

@section('content')
    <h1 class="h5 fw-bold mb-2" style="color: var(--bc-text);">Your session has expired</h1>
    <p class="small text-muted mb-0">Please sign in again to continue.</p>
    <a href="{{ route('login') }}" class="btn btn-bc-primary rounded-pill mt-4 w-100">Sign in</a>
@endsection
