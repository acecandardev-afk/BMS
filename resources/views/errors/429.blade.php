@extends('errors.minimal')

@section('title', 'Too many requests')

@section('content')
    <h1 class="h5 fw-bold mb-2" style="color: var(--bc-text);">Please slow down for a moment</h1>
    <p class="small text-muted mb-0">You’ve tried this a few times in a row. Wait briefly, then try again.</p>
    <a href="{{ route('home') }}" class="btn btn-bc-primary rounded-pill mt-4 w-100">Go to home</a>
@endsection
