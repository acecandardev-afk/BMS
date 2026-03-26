@extends('errors.minimal')

@section('title', 'Something went wrong')

@section('content')
    <h1 class="h5 fw-bold mb-2" style="color: var(--bc-text);">Something went wrong on our side</h1>
    <p class="small text-muted mb-0">Please try again in a few minutes. If the problem continues, contact the barangay office.</p>
    <a href="{{ route('home') }}" class="btn btn-bc-primary rounded-pill mt-4 w-100">Go to home</a>
@endsection
