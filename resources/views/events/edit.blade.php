@extends('layouts.app')

@section('title', 'Edit barangay update')

@section('content')

<div class="py-3 py-md-4 mx-auto" style="max-width: 42rem;">

    <div class="d-flex align-items-start gap-3 mb-4">
        <a href="{{ route('events.index') }}"
           class="bc-back-link text-decoration-none d-inline-flex align-items-center mt-1 flex-shrink-0"
           aria-label="Back to updates">
            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="min-w-0">
            <h2 class="h5 mb-1 bc-page-title">Edit update</h2>
            <p class="small text-muted mb-0">Changes apply immediately on the public Events page.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('events.update', $event) }}" class="d-flex flex-column gap-4">
        @csrf
        @method('PUT')

        <div class="card bc-card p-3 p-md-4 bc-form-panel">
            <div class="mb-3">
                <label for="title" class="form-label small fw-medium">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" required maxlength="255"
                       class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}">
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-0">
                <label for="body" class="form-label small fw-medium">Details <span class="text-danger">*</span></label>
                <textarea name="body" id="body" rows="10" required
                          class="form-control {{ $errors->has('body') ? 'is-invalid' : '' }}">{{ old('body', $event->body) }}</textarea>
                @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-bc-primary">Save changes</button>
            <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>

@endsection
