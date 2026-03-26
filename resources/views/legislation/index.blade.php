@extends('layouts.app')

@section('title', 'Legislation')

@section('content')

<div class="py-4">

    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h5 mb-0 bc-page-title">Legislation</h2>
            <p class="small mb-0 bc-page-subtitle">Barangay ordinances and resolutions repository.</p>
        </div>
        @can('create', \App\Models\Legislation::class)
        <a href="{{ route('legislation.create') }}"
           class="btn btn-bc-primary btn-sm rounded-pill d-inline-flex align-items-center gap-2">
            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Legislation
        </a>
        @endcan
    </div>

    <!-- Filters -->
    <form method="GET" class="bc-filter-bar mb-4" role="search" aria-label="Filter legislation">
        <div class="bc-filter-bar-inner">
            <div class="bc-filter-field-search bc-filter-field-search--wide">
                <label for="leg-search" class="visually-hidden">Search legislation</label>
                <input type="search" id="leg-search" name="search" value="{{ request('search') }}"
                       placeholder="Search title, number…"
                       class="form-control form-control-sm" autocomplete="off">
            </div>
            <div class="bc-filter-field-select">
                <label for="leg-type" class="visually-hidden">Type</label>
                <select id="leg-type" name="type" class="form-select form-select-sm" title="Type">
                    <option value="">All types</option>
                    @foreach($types as $key => $label)
                        <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            @can('manage-legislation')
            <div class="bc-filter-field-select">
                <label for="leg-status" class="visually-hidden">Status</label>
                <select id="leg-status" name="status" class="form-select form-select-sm" title="Status">
                    <option value="">All statuses</option>
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            @endcan
            <div class="bc-filter-field-tag">
                <label for="leg-tag" class="visually-hidden">Tag</label>
                <input type="text" id="leg-tag" name="tag" value="{{ request('tag') }}"
                       placeholder="Tag…"
                       class="form-control form-control-sm" autocomplete="off">
            </div>
            <div class="bc-filter-actions">
                <button type="submit" class="btn btn-bc-primary btn-sm">Filter</button>
                <a href="{{ route('legislation.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </div>
    </form>

    <!-- List -->
    <div class="d-flex flex-column gap-3">
        @forelse($legislations as $legislation)
        <div class="card bc-card p-3 p-md-4 bc-form-panel">
            <div class="d-flex align-items-start justify-content-between gap-4">
                <div class="flex-grow-1 min-w-0">
                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                        <x-badge :status="$legislation->type" :label="$legislation->type_name"/>
                        <x-badge :status="$legislation->status" :label="$legislation->status_name"/>
                        <span class="small text-muted">No. {{ $legislation->number }}, Series of {{ $legislation->series }}</span>
                    </div>
                    <h3 class="fw-semibold text-dark mb-1">{{ $legislation->title }}</h3>
                    @if($legislation->description)
                    <p class="small text-muted mb-2 text-break">{{ Str::limit($legislation->description, 100) }}</p>
                    @endif
                    <div class="d-flex flex-wrap gap-3 small text-muted">
                        @if($legislation->date_enacted)
                            <span>Enacted: {{ $legislation->date_enacted->format('M d, Y') }}</span>
                        @endif
                        @if($legislation->date_effective)
                            <span>Effective: {{ $legislation->date_effective->format('M d, Y') }}</span>
                        @endif
                        @if($legislation->tags)
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($legislation->tags as $tag)
                            <span class="badge bc-badge bg-secondary bg-opacity-25 text-secondary">{{ $tag }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-shrink-0">
                    <a href="{{ route('legislation.show', $legislation) }}"
                       class="small text-primary text-decoration-none fw-medium">View</a>
                    @can('update', $legislation)
                    <a href="{{ route('legislation.edit', $legislation) }}"
                       class="small text-secondary text-decoration-none fw-medium">Edit</a>
                    @endcan
                    @can('delete', $legislation)
                    <form method="POST" action="{{ route('legislation.destroy', $legislation) }}"
                          data-bc-confirm="Delete this legislation item?" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-link btn-sm p-0 text-danger text-decoration-none small fw-medium">Delete</button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
        @empty
        <div class="card bc-card p-5 text-center">
            <svg class="text-muted mb-3" style="width: 3rem; height: 3rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
            </svg>
            <p class="text-muted small fw-medium mb-0">No legislation found.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        <x-pagination :paginator="$legislations"/>
    </div>

</div>

@endsection
