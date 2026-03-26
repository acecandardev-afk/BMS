@extends('layouts.app')

@section('title', 'Legislation Detail')

@section('content')

<div class="py-4" style="max-width: 56rem;">

    <!-- Header -->
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('legislation.index') }}"
           class="bc-back-link text-decoration-none d-inline-flex align-items-center">
            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-grow-1">
            <h2 class="h5 mb-0 bc-page-title">Legislation Detail</h2>
            <p class="small text-muted mb-0">{{ $legislation->full_title }}</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            @can('update', $legislation)
            <a href="{{ route('legislation.edit', $legislation) }}" class="btn btn-bc-primary btn-sm rounded-pill">
                Edit
            </a>
            @endcan
            @can('delete', $legislation)
            <form method="POST" action="{{ route('legislation.destroy', $legislation) }}"
                  data-bc-confirm="Delete this legislation item?" class="d-inline">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    Delete
                </button>
            </form>
            @endcan
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-12 col-lg-8">
            <div class="d-flex flex-column gap-4">
                <!-- Title & Description -->
                <div class="card bc-card p-3 p-md-4 bc-form-panel">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <x-badge :status="$legislation->type" :label="$legislation->type_name"/>
                        <x-badge :status="$legislation->status" :label="$legislation->status_name"/>
                    </div>
                    <h1 class="h4 fw-bold text-dark mb-2">{{ $legislation->title }}</h1>
                    <p class="small text-muted mb-4">{{ $legislation->full_title }}</p>

                    @if($legislation->description)
                    <div class="bg-light rounded p-3 mb-4">
                        <p class="small fw-semibold text-muted mb-1">Abstract / Description</p>
                        <p class="small text-secondary mb-0 lh-base">{{ $legislation->description }}</p>
                    </div>
                    @endif

                    @if($legislation->tags && count($legislation->tags) > 0)
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        @foreach($legislation->tags as $tag)
                        <a href="{{ route('legislation.index') }}?tag={{ $tag }}"
                           class="badge bg-primary bg-opacity-25 text-primary text-decoration-none">
                            {{ $tag }}
                        </a>
                        @endforeach
                    </div>
                    @endif

                    @if($legislation->file_url)
                    <a href="{{ $legislation->file_url }}" target="_blank"
                       class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-2">
                        <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download Document
                    </a>
                    @endif
                </div>

                <!-- Full Content -->
                @if($legislation->content)
                <div class="card bc-card p-3 p-md-4 bc-form-panel">
                    <h3 class="bc-form-section-title">
                        Full Text
                    </h3>
                    <div class="text-secondary lh-base small" style="white-space: pre-wrap;">{{ $legislation->content }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-12 col-lg-4">
            <div class="d-flex flex-column gap-4">
                <!-- Details -->
                <div class="card bc-card p-3 p-md-4 bc-form-panel">
                    <h3 class="small fw-semibold text-secondary mb-3">Details</h3>
                    <div class="d-flex flex-column gap-2 small">
                        <div>
                            <p class="text-muted small mb-0">Number</p>
                            <p class="fw-medium text-dark mb-0">No. {{ $legislation->number }}</p>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Series</p>
                            <p class="fw-medium text-dark mb-0">Series of {{ $legislation->series }}</p>
                        </div>
                        @if($legislation->date_enacted)
                        <div>
                            <p class="text-muted small mb-0">Date Enacted</p>
                            <p class="fw-medium text-dark mb-0">{{ $legislation->date_enacted->format('F d, Y') }}</p>
                        </div>
                        @endif
                        @if($legislation->date_effective)
                        <div>
                            <p class="text-muted small mb-0">Date Effective</p>
                            <p class="fw-medium text-dark mb-0">{{ $legislation->date_effective->format('F d, Y') }}</p>
                        </div>
                        @endif
                        <div>
                            <p class="text-muted small mb-0">Uploaded By</p>
                            <p class="fw-medium text-dark mb-0">{{ $legislation->uploader?->name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-muted small mb-0">Date Added</p>
                            <p class="fw-medium text-dark mb-0">{{ $legislation->created_at->format('M d, Y') }}</p>
                        </div>
                        @if($legislation->updated_at != $legislation->created_at)
                        <div>
                            <p class="text-muted small mb-0">Last Updated</p>
                            <p class="fw-medium text-dark mb-0">{{ $legislation->updated_at->format('M d, Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Related by Tag -->
                @if($legislation->tags && count($legislation->tags) > 0)
                <div class="card bc-card p-3 p-md-4 bc-form-panel">
                    <h3 class="small fw-semibold text-secondary mb-3">Tags</h3>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($legislation->tags as $tag)
                        <a href="{{ route('legislation.index') }}?tag={{ $tag }}"
                           class="badge bg-primary bg-opacity-25 text-primary text-decoration-none">
                            {{ $tag }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection
