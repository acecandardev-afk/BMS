@extends('layouts.app')

@section('title', 'Blotter Records')

@section('content')

<div class="py-4">

    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="h5 mb-0 bc-page-title">Blotter Records</h2>
            <p class="small mb-0 bc-page-subtitle">Manage dispute and incident records.</p>
        </div>
        @can('manage-blotter')
        <a href="{{ route('blotter.create') }}"
           class="btn btn-bc-primary btn-sm rounded-pill d-inline-flex align-items-center gap-2">
            <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            File Blotter
        </a>
        @endcan
    </div>

    <!-- Filters -->
    <form method="GET" class="bc-filter-bar mb-4" role="search" aria-label="Filter blotter records">
        <div class="bc-filter-bar-inner">
            <div class="bc-filter-field-search bc-filter-field-search--wide">
                <label for="bl-search" class="visually-hidden">Search blotter</label>
                <input type="search" id="bl-search" name="search" value="{{ request('search') }}"
                       placeholder="Case #, names, narrative…"
                       class="form-control form-control-sm" autocomplete="off">
            </div>
            <div class="bc-filter-field-select bc-filter-field-select--wide">
                <label for="bl-type" class="visually-hidden">Incident type</label>
                <select id="bl-type" name="type" class="form-select form-select-sm" title="Type">
                    <option value="">All types</option>
                    @foreach($incident_types as $key => $label)
                        <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="bc-filter-field-select">
                <label for="bl-status" class="visually-hidden">Status</label>
                <select id="bl-status" name="status" class="form-select form-select-sm" title="Status">
                    <option value="">All statuses</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="bc-filter-actions">
                <button type="submit" class="btn btn-bc-primary btn-sm">Filter</button>
                <a href="{{ route('blotter.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </div>
    </form>

    <!-- Table -->
    <div class="card bc-card overflow-hidden">
        <div class="table-responsive">
            <table class="table bc-table table-hover table-striped mb-0 small">
                <thead>
                    <tr>
                        <th class="text-uppercase small fw-semibold text-muted">Blotter #</th>
                        <th class="text-uppercase small fw-semibold text-muted">Complainant</th>
                        <th class="text-uppercase small fw-semibold text-muted">Respondent</th>
                        <th class="text-uppercase small fw-semibold text-muted">Incident Type</th>
                        <th class="text-uppercase small fw-semibold text-muted">Date</th>
                        <th class="text-uppercase small fw-semibold text-muted">Status</th>
                        <th class="text-uppercase small fw-semibold text-muted">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blotters as $blotter)
                    <tr>
                        <td class="fw-medium text-dark">{{ $blotter->blotter_number }}</td>
                        <td class="text-secondary">{{ $blotter->complainant_name }}</td>
                        <td class="text-secondary">{{ $blotter->respondent_name }}</td>
                        <td class="text-secondary">{{ $blotter->incident_type_name }}</td>
                        <td class="text-muted">{{ $blotter->incident_date->format('M d, Y') }}</td>
                        <td>
                            <x-badge :status="$blotter->status"/>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('blotter.show', $blotter) }}"
                                   class="text-primary text-decoration-none small fw-medium">View</a>
                                @can('update', $blotter)
                                <a href="{{ route('blotter.edit', $blotter) }}"
                                   class="text-secondary text-decoration-none small fw-medium">Edit</a>
                                @endcan
                                @can('delete', $blotter)
                                <form method="POST" action="{{ route('blotter.destroy', $blotter) }}"
                                      data-bc-confirm="Delete this blotter record? This cannot be undone.">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-link btn-sm p-0 text-danger text-decoration-none small fw-medium">
                                        Delete
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5 small">
                            No blotter records found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-top">
            <x-pagination :paginator="$blotters"/>
        </div>
    </div>

</div>

@endsection
