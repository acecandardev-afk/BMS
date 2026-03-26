@extends('errors.minimal')

@section('title', 'Access not allowed')

@section('content')
    <h1 class="h5 fw-bold mb-2" style="color: var(--bc-text);">You don’t have access to this page</h1>
    <p class="small text-muted mb-0">If you think this is a mistake, please contact the barangay office.</p>
    <a href="{{ route('home') }}" class="btn btn-bc-primary rounded-pill mt-4 w-100">Go to home</a>
@endsection
