@extends('errors.minimal')

@section('title', 'Request could not be completed')

@section('content')
    <h1 class="h5 fw-bold mb-2" style="color: var(--bc-text);">We couldn’t complete that request</h1>
    <p class="small text-muted mb-0">Please go back, refresh the page, or try again in a moment. If you need help, contact the barangay office.</p>
    <a href="{{ route('home') }}" class="btn btn-bc-primary rounded-pill mt-4 w-100">Go to home</a>
@endsection
